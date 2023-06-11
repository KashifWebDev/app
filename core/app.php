<?php

use PHPMailer\PHPMailer\PHPMailer;

header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

$hostname = "localhost";

$user = "root";
$pwd = "";
$db = "projects_gymapp";


$user = "u953547654_app";
$pwd = "App@12345";
$db = "u953547654_app";


$con = mysqli_connect($hostname, $user, $pwd, $db);

$response = array(
    'status' => false,
    'message' => '',
    'data' => array()
);
if ($con->connect_errno) {
    echo "Failed to connect to MySQL: " . $con->connect_error;
    exit();
}

function generateOPT($con, $email){
    $otp = rand(1000, 9999);
    $query = "UPDATE users SET verification_code = ? WHERE email = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'is', $otp, $email);
    mysqli_stmt_execute($stmt);

    if(!sendEmailOTP($email, $otp)){
        echo "Email err"; exit(); die();
    }
    return $otp;
}

function sendEmailOTP($email, $otp) {
    require '../vendor/autoload.php';
    // Create a new PHPMailer instance
    $mail = new PHPMailer();

    // SMTP Configuration (Replace with your own SMTP settings)
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->Username = 'gym@kashifali.me';
    $mail->Password = 'Gym@1234';
    $mail->SMTPSecure = 'tls';

    // Set the From and To addresses
    $mail->setFrom('gym@kashifali.me', 'Gym APP');
    $mail->addAddress($email);

    // Set the subject and body of the email
    $mail->Subject = 'OTP Verification';
    $mail->Body = 'Your OTP is: ' . $otp;

    // Send the email
    if ($mail->send()) {
        return true;
    } else {
        return false;
    }
}