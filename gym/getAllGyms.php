<?php

require '../core/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch all gyms
    $query = "SELECT * FROM gyms";
    $result = mysqli_query($con, $query);

    if ($result) {
        $gyms = array();

        // Loop through the result set and fetch gym data
        while ($row = mysqli_fetch_assoc($result)) {
            $gym = array(
                'id' => $row['id'],
                'user_id' => (int) $row['user_id'],
                'gymName' => $row['name'],
                'sessionType' => $row['sessions'],
                'gender' => $row['gender'],
                'address' => $row['address'],
                'lat' => (float) $row['lat'],
                'long' => (float) $row['loong'],
                'img' => $_SERVER['DOCUMENT_ROOT'].'/uploads/gyms/'.$row['img']
            );

            $gyms[] = $gym;
        }

        $response['status'] = true;
        $response['message'] = "Gyms fetched successfully";
        $response['data'] = $gyms;
        $status = 200;
    } else {
        $response['message'] = "Failed to fetch gyms";
        $status = 404;
    }
} else {
    $response['message'] = "Invalid request method";
    $status = 404;
}

echo json_encode($response);
http_response_code($status);
