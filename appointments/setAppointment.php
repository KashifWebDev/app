<?php
require '../core/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data
    $userID = $_POST['user_id'];
    $gymID = $_POST['gym_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Insert the appointment into the appointments table
    $query = "INSERT INTO appointments (user_id, gym_id, appointment_date, appointment_start_time) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'iiss', $userID, $gymID, $date, $time);

    if (mysqli_stmt_execute($stmt)) {
        $response['status'] = true;
        $response['message'] = "Appointment set successfully";
        $status = 200;
    } else {
        $response['status'] = false;
        $response['message'] = "Failed to set appointment";
        $status = 500;
    }
} else {
    $response['status'] = false;
    $response['message'] = "Invalid request method";
    $status = 404;
}

echo json_encode($response);
http_response_code($status);
?>
