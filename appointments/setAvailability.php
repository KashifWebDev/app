<?php
require '../core/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Parse POST parameters
    $userID = $_POST['user_id'] ?? null;
    $startDate = $_POST['startDate'] ?? null;
    $endDate = $_POST['endDate'] ?? null;
    $startTime = $_POST['startTime'] ?? null;
    $endTime = $_POST['endTime'] ?? null;
    $days = $_POST['days'] ?? null;

    // Check if user_id is provided
    if ($userID !== null) {
        // Construct SQL UPDATE query
        $query = "UPDATE users SET startDate = ?, endDate = ?, startTime = ?, endTime = ?, days = ? WHERE id = ?";
        $stmt = mysqli_prepare($con, $query);

        // Bind parameters to the query
        mysqli_stmt_bind_param($stmt, 'sssssi', $startDate, $endDate, $startTime, $endTime, $days, $userID);

        // Execute the update query
        if (mysqli_stmt_execute($stmt)) {
            $response['status'] = true;
            $response['message'] = "Availability updated successfully";
            $status = 200;
        } else {
            $response['status'] = false;
            $response['message'] = "Failed to update availability";
            $status = 400;
        }
    } else {
        $response['status'] = false;
        $response['message'] = "Missing user_id parameter";
        $status = 400;
    }
} else {
    $response['status'] = false;
    $response['message'] = "Invalid request method";
    $status = 404;
}

echo json_encode($response);
http_response_code($status);
?>
