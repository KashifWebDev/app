<?php
require '../core/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['user_id'])) {
        $response['status'] = false;
        $response['message'] = "Missing user_id parameter";
        $status = 400;
    } else {
        $userID = $_GET['user_id'];

        // Query to fetch availability information for the specified gym owner/trainer
        $query = "SELECT startTime, endTime, startDate, endDate, days FROM users WHERE id = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'i', $userID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $response['status'] = true;
            $response['message'] = "Availability fetched successfully";
            $response['data'] = $row;
            $status = 200;
        } else {
            $response['status'] = true;
            $response['message'] = "No availability found for the specified user";
            $response['data'] = [];
            $status = 200;
        }
    }
} else {
    $response['status'] = false;
    $response['message'] = "Invalid request method";
    $status = 404;
}

echo json_encode($response);
http_response_code($status);
?>
