<?php
require '../core/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['user_id'])) {
        $response['status'] = false;
        $response['message'] = "Missing user_id parameter";
        $status = 400;
    } else {
        $userID = $_GET['user_id'];

        $query = "SELECT 
                    appointments.*, 
                    gyms.name AS gym_name, 
                    users.fullName AS user_name 
                  FROM 
                    appointments 
                  INNER JOIN 
                    gyms ON appointments.gym_id = gyms.id 
                  INNER JOIN 
                    users ON appointments.user_id = users.id 
                  WHERE 
                    appointments.user_id = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'i', $userID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $appointments = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $appointments[] = $row;
        }

        $response['status'] = true;
        $response['message'] = "Appointments fetched successfully";
        $response['data'] = $appointments;
        $status = 200;
    }
} else {
    $response['status'] = false;
    $response['message'] = "Invalid request method";
    $status = 404;
}

echo json_encode($response);
http_response_code($status);
?>
