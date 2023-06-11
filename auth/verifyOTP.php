<?php

require '../core/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $otpCode = filter_input(INPUT_POST, 'otp_code');

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if ($user['verification_code'] == $otpCode) {
            // Update 'verified' column to 1
            $updateQuery = "UPDATE users SET verified = 1 WHERE email = '$email'";
            $updateResult = mysqli_query($con, $updateQuery);

            if ($updateResult) {
                $response['status'] = true;
                $response['message'] = "OTP verification successful. Account verified.";
                $status = 200;
            } else {
                $response['message'] = "Failed to update account verification status.";
                $status = 500;
            }
        } else {
            $response['message'] = "Incorrect OTP. Please enter the correct OTP code.";
            $status = 400;
        }
    } else {
        $response['message'] = "User not found with the provided email.";
        $status = 404;
    }
} else {
    $response['message'] = "Invalid request method";
    $status = 404;
}

echo json_encode($response);
http_response_code($status);
