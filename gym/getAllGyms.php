<?php
require '../core/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['user_id'])) {
        $response['status'] = false;
        $response['message'] = "Missing user_id parameter";
        $status = 400;
    } else {
        $userId = $_GET['user_id'];
        $query = "SELECT * FROM gyms WHERE user_id = '$userId'";
        $result = mysqli_query($con, $query);

        if ($result) {
            $gyms = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $gyms[] = array(
                    'id' => $row['id'],
                    'user_id' => $row['user_id'],
                    'name' => $row['name'],
                    'sessions' => $row['sessions'],
                    'gender' => $row['gender'],
                    'address' => $row['address'],
                    'lat' => $row['lat'],
                    'loong' => $row['loong'],
                    'img' => $row['img']
                );
            }

            $response['status'] = true;
            $response['message'] = "Gyms fetched successfully";
            $response['data'] = $gyms;
            $status = 200;
        } else {
            $response['message'] = "Failed to fetch gyms";
            $status = 404;
        }
    }
} else {
    $response['message'] = "Invalid request method";
    $status = 404;
}

echo json_encode($response);
http_response_code($status);
