<?php
require '../core/app.php';

if (isset($_POST['email'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    // Generate a random OTP
    $otp = rand(1000, 9999);

    // Store the OTP in the database for verification
    // (Update the 'verification_code' column in the 'users' table for the specific user)
    $query = "UPDATE users SET verification_code = ? WHERE email = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'is', $otp, $email);
    mysqli_stmt_execute($stmt);

    // Send the email with the OTP
    $subject = 'OTP Verification';
    $message = 'Your OTP is: ' . $otp;
    $headers = 'From: your_email@example.com' . "\r\n";
    $headers .= 'Reply-To: your_email@example.com' . "\r\n";
    $headers .= 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-Type: text/html; charset=utf-8' . "\r\n";

    if (mail($email, $subject, $message, $headers)) {
        $response['status'] = true;
        $response['message'] = "OTP sent successfully";
        $status = 200;
    } else {
        $response['message'] = "Failed to send OTP";
        $status = 404;
    }
} else {
    $response['message'] = "Must pass email field";
    $status = 404;
}

echo json_encode($response);
http_response_code($status);
