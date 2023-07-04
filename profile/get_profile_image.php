<?php

require '../core/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['user_id'])) {
        $userId = $_GET['user_id'];

        // Check if the user exists
        $checkUserQuery = "SELECT img FROM users WHERE id = $userId";
        $result = mysqli_query($con, $checkUserQuery);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $img = $row['img'];

            if (!empty($img)) {
                $response['status'] = true;
                $response['message'] = "Profile image found";
                $response['data'] = ['img' => $appPath . '/uploads/users/' . $img];
                $status = 200;
            } else {
                $response['status'] = false;
                $response['message'] = "Profile image not found for the user";
                $status = 404;
            }
        } else {
            $response['status'] = false;
            $response['message'] = "User not found";
            $status = 404;
        }
    } else {
        $response['status'] = false;
        $response['message'] = "Missing user_id parameter";
        $status = 400;
    }
} else {
    $response['message'] = "Invalid request method";
    $status = 404;
}

echo json_encode($response);
http_response_code($status);
