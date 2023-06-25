<?php
require '../core/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['user_id']) || !isset($_POST['gym_id']) || !isset($_POST['amount'])) {
        $response['status'] = false;
        $response['message'] = "Missing required parameters";
        $status = 400;
    } else {
        $userID = $_POST['user_id'];
        $gymID = $_POST['gym_id'];
        $amount = $_POST['amount'];
        $paymentDate = date('Y-m-d');
        $paymentResponse = "payment_Response_FROM_API";

        $query = "INSERT INTO user_payments (date, payment_response, user_id, gym_id, amount) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'ssiii', $paymentDate, $paymentResponse, $userID, $gymID, $amount);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $response['status'] = true;
            $response['message'] = "Payment data stored successfully";
            $status = 200;
        } else {
            $response['status'] = false;
            $response['message'] = "Failed to store payment data";
            $status = 500;
        }
    }
} else {
    $response['status'] = false;
    $response['message'] = "Invalid request method";
    $status = 404;
}

echo json_encode($response);
http_response_code($status);
