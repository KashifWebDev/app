<?php
require '../core/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requiredParameters = ['gym_id', 'user_id'];
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
        $gymId = $_POST['gym_id'];
        $userId = $_POST['user_id'];

        // Check if user owns the gym
        $checkOwnershipQuery = "SELECT user_id FROM gyms WHERE id = $gymId";
        $result = mysqli_query($con, $checkOwnershipQuery);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $ownerId = $row['user_id'];

            // Compare the owner ID with the provided user ID
            if ($ownerId != $userId) {
                $response['status'] = false;
                $response['message'] = "You do not own this gym";
                $status = 403;
                echo json_encode($response);
                http_response_code($status);
                exit();
            }
        } else {
            $response['status'] = false;
            $response['message'] = "Gym not found";
            $status = 404;
            echo json_encode($response);
            http_response_code($status);
            exit();
        }

        // Delete the gym
        $deleteQuery = "DELETE FROM gyms WHERE id = $gymId";
        $result = mysqli_query($con, $deleteQuery);

        if ($result) {
            $response['status'] = true;
            $response['message'] = "Gym deleted successfully";
            $status = 200;
        } else {
            $response['message'] = "Failed to delete gym";
            $status = 404;
        }
    }
} else {
    $response['message'] = "Invalid request method";
    $status = 404;
}

echo json_encode($response);
http_response_code($status);
