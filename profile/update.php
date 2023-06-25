<?php

require '../core/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requiredParameters = ['user_id', 'fullName', 'phone', 'address'];
    $missingParameters = [];

    foreach ($requiredParameters as $parameter) {
        if (!isset($_POST[$parameter])) {
            $missingParameters[] = $parameter;
        }
    }

    if (!empty($missingParameters)) {
        $response['status'] = false;
        $response['message'] = "Missing parameters: " . implode(', ', $missingParameters);
        $status = 400;
    } else {
        $userId = $_POST['user_id'];
        $fullName = $_POST['fullName'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $password = $_POST['password'] ?? null;

        // Check if the user exists
        $checkUserQuery = "SELECT id FROM users WHERE id = $userId";
        $result = mysqli_query($con, $checkUserQuery);

        if ($result && mysqli_num_rows($result) > 0) {
            // Update the user profile
            $updateQuery = "UPDATE users SET fullName = '$fullName', phone = '$phone', address = '$address'";

            // Update the password if provided
            if (!empty($password)) {
                $updateQuery .= ", password = '$password'";
            }

            $updateQuery .= " WHERE id = $userId";

            $result = mysqli_query($con, $updateQuery);

            if ($result) {
                $response['status'] = true;
                $response['message'] = "User profile updated successfully";
                unset($_POST['user_id']);
                unset($_POST['password']);
                $response['data'] = $_POST;
                $status = 200;
            } else {
                $response['message'] = "Failed to update user profile";
                $status = 404;
            }
        } else {
            $response['status'] = false;
            $response['message'] = "User not found";
            $status = 404;
        }
    }
} else {
    $response['message'] = "Invalid request method";
    $status = 404;
}

echo json_encode($response);
http_response_code($status);
