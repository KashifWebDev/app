<?php
require '../core/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userLat = isset($_GET['lat']) ? $_GET['lat'] : null;
    $userLng = isset($_GET['long']) ? $_GET['long'] : null;
    $radius = isset($_GET['radius']) ? $_GET['radius'] : null;
    $sessionType = isset($_GET['session']) ? $_GET['session'] : null;
    $gender = isset($_GET['gender']) ? $_GET['gender'] : null;
    $fee = isset($_GET['fee']) ? $_GET['fee'] : null;

    if ($userLat !== null && $userLng !== null && $radius === null) {
        // If lat and long are provided without radius, list all gyms without applying the radius filter
        $gyms = getAllGyms($sessionType, $gender, $fee);
    } else {
        // Filter gyms within the specified radius
        $gyms = getGymsWithinRadius($userLat, $userLng, $radius, $sessionType, $gender, $fee);
    }

    if (!empty($gyms)) {
        $response['status'] = true;
        $response['message'] = "Gyms fetched successfully";
        $response['data'] = $gyms;
        $status = 200;
    } else {
        $response['status'] = true;
        $response['message'] = "No gyms found";
        $response['data'] = [];
        $status = 200;
    }
} else {
    $response['status'] = false;
    $response['message'] = "Invalid request method";
    $status = 404;
}

echo json_encode($response);
http_response_code($status);

function getGymsWithinRadius($userLat, $userLng, $radius, $sessionType, $gender, $fee)
{
    global $con; // Assuming $con is the database connection object

    $gyms = [];

    if ($userLat !== null && $userLng !== null && $radius !== null) {
        $query = "SELECT id, name, sessions, gender, address, lat, loong, img, fees
              FROM gyms";

        $params = [];

        if ($sessionType !== null) {
            $query .= " WHERE sessions = ?";
            $params[] = $sessionType;
        }
        if ($gender !== null) {
            $query .= ($sessionType !== null) ? " AND gender = ?" : " WHERE gender = ?";
            $params[] = $gender;
        }
        if ($fee !== null) {
            $query .= ($sessionType !== null || $gender !== null) ? " AND fees <= ?" : " WHERE fees <= ?";
            $params[] = $fee;
        }

        $stmt = mysqli_prepare($con, $query);

        if (!empty($params)) {
            $paramTypes = generateParamTypes($params);
            $stmt_params = array_merge([$stmt, $paramTypes], getParamReferences($params));
            call_user_func_array('mysqli_stmt_bind_param', $stmt_params);
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $gymLat = (float) $row['lat'];
                $gymLng = (float) $row['loong'];

                $distance = calculateDistance($userLat, $userLng, $gymLat, $gymLng);

                if ($distance <= $radius) {
                    $row['lat'] = (float) $row['lat'];
                    $row['loong'] = (float) $row['loong'];
                    $row['img'] = $GLOBALS['appPath'] . '/uploads/gyms/' . $row['img'];
                    $gyms[] = $row;
                }
            }
        }
    } else {
        $gyms = getAllGyms($sessionType, $gender, $fee);
    }

    return $gyms;
}

function getAllGyms($sessionType, $gender, $fee)
{
    global $con; // Assuming $con is the database connection object

    $gyms = [];

    $query = "SELECT id, name, sessions, gender, address, lat, loong, img, fees
              FROM gyms";

    $params = [];

    if ($sessionType !== null) {
        $query .= " WHERE sessions = ?";
        $params[] = &$sessionType;
    }
    if ($gender !== null) {
        $query .= ($sessionType !== null) ? " AND gender = ?" : " WHERE gender = ?";
        $params[] = &$gender;
    }
    if ($fee !== null) {
        $query .= ($sessionType !== null || $gender !== null) ? " AND fees <= ?" : " WHERE fees <= ?";
        $params[] = &$fee;
    }

    $stmt = mysqli_prepare($con, $query);

    if (!empty($params)) {
        $paramTypes = generateParamTypes($params);
        $stmt_params = array_merge([$stmt, $paramTypes], $params);
        call_user_func_array('mysqli_stmt_bind_param', $stmt_params);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $row['lat'] = (float) $row['lat'];
            $row['loong'] = (float) $row['loong'];
            $row['img'] = $GLOBALS['appPath'] . '/uploads/gyms/' . $row['img'];
            $gyms[] = $row;
        }
    }

    return $gyms;
}


function calculateDistance($lat1, $lng1, $lat2, $lng2)
{
    $earthRadius = 6371; // Radius of the Earth in kilometers

    $latDiff = deg2rad($lat2 - $lat1);
    $lngDiff = deg2rad($lng2 - $lng1);

    $a = sin($latDiff / 2) * sin($latDiff / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($lngDiff / 2) * sin($lngDiff / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    $distance = $earthRadius * $c;

    return $distance;
}

function generateParamTypes($params)
{
    $types = '';
    foreach ($params as $param) {
        if (is_int($param)) {
            $types .= 'i';
        } elseif (is_double($param)) {
            $types .= 'd';
        } else {
            $types .= 's';
        }
    }
    return $types;
}

function getParamReferences($params)
{
    $references = [];
    foreach ($params as $key => $param) {
        $references[$key] = &$params[$key];
    }
    return $references;
}
