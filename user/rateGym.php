<?php
require '../core/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requiredParameters = ['gym_id', 'user_id', 'rating'];
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
        $rating = $_POST['rating'];

        // Check if the gym exists
        $checkGymQuery = "SELECT id FROM gyms WHERE id = $gymId";
        $result = mysqli_query($con, $checkGymQuery);

        if ($result && mysqli_num_rows($result) > 0) {
            // Check if the user has already rated the gym
            $checkRatingQuery = "SELECT id FROM ratings WHERE gym_id = $gymId AND user_id = $userId";
            $result = mysqli_query($con, $checkRatingQuery);

            if ($result && mysqli_num_rows($result) > 0) {
                // Update the existing rating
                $updateRatingQuery = "UPDATE ratings SET rating = $rating WHERE gym_id = $gymId AND user_id = $userId";
                $result = mysqli_query($con, $updateRatingQuery);

                if ($result) {
                    $response['status'] = true;
                    $response['message'] = "Rating updated successfully";
                    $status = 200;
                } else {
                    $response['message'] = "Failed to update rating";
                    $status = 500;
                }
            } else {
                // Insert a new rating
                $insertRatingQuery = "INSERT INTO ratings (gym_id, user_id, rating) VALUES ($gymId, $userId, $rating)";
                $result = mysqli_query($con, $insertRatingQuery);

                if ($result) {
                    $response['status'] = true;
                    $response['message'] = "Rating added successfully";
                    $status = 200;
                } else {
                    $response['message'] = "Failed to add rating";
                    $status = 500;
                }
            }
        } else {
            $response['status'] = false;
            $response['message'] = "Gym not found";
            $status = 404;
        }
    }
} else {
    $response['message'] = "Invalid request method";
    $status = 404;
}

echo json_encode($response);
http_response_code($status);
