<?php
require '../core/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['gym_id'])) {
        $response['status'] = false;
        $response['message'] = "Missing gym_id parameter";
        $status = 400;
    } else {
        $gymID = $_GET['gym_id'];
        $userID = $_GET['user_id'] ?? null;

        // Fetch gym details from the gyms table
        $query = "SELECT * FROM gyms WHERE id = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'i', $gymID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            $gym = mysqli_fetch_assoc($result);

            if ($gym) {
                $gym['img'] = $appPath.'/uploads/gyms/'.$gym['img'];

                // Check if the user has already submitted ratings for the gym
                if ($userID !== null) {
                    $checkRatingQuery = "SELECT COUNT(*) as count FROM ratings WHERE user_id = ? AND gym_id = ?";
                    $stmt = mysqli_prepare($con, $checkRatingQuery);
                    mysqli_stmt_bind_param($stmt, 'ii', $userID, $gymID);
                    mysqli_stmt_execute($stmt);
                    $ratingResult = mysqli_stmt_get_result($stmt);
                    $ratingCount = mysqli_fetch_assoc($ratingResult)['count'];

                    $gym['rating_submitted'] = ($ratingCount > 0) ? false : true;
                } else {
                    $gym['rating_submitted'] = true;
                }

                $response['status'] = true;
                $response['message'] = "Gym details fetched successfully";
                $response['data'] = $gym;
                $status = 200;
            } else {
                $response['status'] = false;
                $response['message'] = "Gym not found";
                $status = 404;
            }
        } else {
            $response['status'] = false;
            $response['message'] = "Failed to fetch gym details";
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
?>
