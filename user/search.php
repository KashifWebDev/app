<?php
require '../core/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userLat = $_GET['lat'] ?? null;
    $userLng = $_GET['long'] ?? null;
    $radius = $_GET['radius'] ?? null;
    $sessionType = $_GET['session'] ?? null;
    $gender = $_GET['gender'] ?? null;
    $fee = $_GET['fee'] ?? null;
    $days = $_GET['days'] ?? null;
    $startTime = $_GET['startTime'] ?? null;
    $endTime = $_GET['endTime'] ?? null;

    if ($userLat !== null && $userLng !== null && $radius === null) {
        // If lat and long are provided without radius, list all gyms without applying the radius filter
        $gyms = getAllGyms($sessionType, $gender, $fee, $days, $startTime, $endTime);
    } else {
        // Filter gyms within the specified radius
        $gyms = getGymsWithinRadius($userLat, $userLng, $radius, $sessionType, $gender, $fee, $days, $startTime, $endTime);
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

function getGymsWithinRadius($userLat, $userLng, $radius, $sessionType, $gender, $fee, $days, $startTime, $endTime)
{
    global $con; // Assuming $con is the database connection object

    $gyms = [];

    if ($userLat !== null && $userLng !== null && $radius !== null) {
        $query = "SELECT id, name, sessions, gender, address, lat, loong, img, fees, days, startTime, endTime
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
                    // Check if the gym is available on the specified days and within the specified time range
                    if (checkGymAvailability($row, $days, $startTime, $endTime)) {
                        $row['lat'] = (float) $row['lat'];
                        $row['loong'] = (float) $row['loong'];
                        $row['img'] = $GLOBALS['appPath'] . '/uploads/gyms/' . $row['img'];
                        $gyms[] = $row;
                    }
                }
            }
        }
    } else {
        $gyms = getAllGyms($sessionType, $gender, $fee, $days, $startTime, $endTime);
    }

    return $gyms;
}

function getAllGyms($sessionType, $gender, $fee, $days, $startTime, $endTime)
{
    global $con; // Assuming $con is the database connection object

    $gyms = [];

    $query = "SELECT id, name, sessions, gender, address, lat, loong, img, fees, days, startTime, endTime
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
        $stmt_params = array_merge([$stmt, $paramTypes], getParamReferences($params));
        call_user_func_array('mysqli_stmt_bind_param', $stmt_params);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Check if the gym is available on the specified days and within the specified time range
            if (checkGymAvailability($row, $days, $startTime, $endTime)) {
                $row['lat'] = (float) $row['lat'];
                $row['loong'] = (float) $row['loong'];
                $row['img'] = $GLOBALS['appPath'] . '/uploads/gyms/' . $row['img'];
                $gyms[] = $row;
            }
        }
    }

    return $gyms;
}

function checkGymAvailability($gym, $days, $startTime, $endTime)
{
    if ($days !== null) {
        $gymDays = explode(',', $gym['days']);
        $userDays = explode(',', $days);
        $intersect = array_intersect($gymDays, $userDays);
        if (empty($intersect)) {
            return false;
        }
    }

    if ($startTime !== null && $endTime !== null) {
        $gymStartTime = strtotime($gym['startTime']);
        $gymEndTime = strtotime($gym['endTime']);
        $userStartTime = strtotime($startTime);
        $userEndTime = strtotime($endTime);

        if ($gymStartTime === false || $gymEndTime === false) {
            return false; // Skip gyms with default time '00:00:00'
        }

        if ($userStartTime < $gymStartTime || $userEndTime > $gymEndTime) {
            return false;
        }
    }

    return true;
}

function calculateDistance($lat1, $lng1, $lat2, $lng2)
{
    $earthRadius = 6371; // in kilometers

    $dLat = deg2rad($lat2 - $lat1);
    $dLng = deg2rad($lng2 - $lng1);

    $a = sin($dLat / 2) * sin($dLat / 2) +
        cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
        sin($dLng / 2) * sin($dLng / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    $distance = $earthRadius * $c;

    return $distance;
}

function generateParamTypes($params)
{
    $paramTypes = "";
    foreach ($params as $param) {
        if (is_string($param)) {
            $paramTypes .= "s";
        } elseif (is_int($param)) {
            $paramTypes .= "i";
        } elseif (is_double($param)) {
            $paramTypes .= "d";
        }
    }
    return $paramTypes;
}

function getParamReferences($params)
{
    $paramReferences = [];
    foreach ($params as $key => $value) {
        $paramReferences[] = &$params[$key];
    }
    return $paramReferences;
}
