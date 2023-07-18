<?php
require '../core/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['user_id']) || !isset($_POST['gym_id']) || !isset($_POST['rating'])) {
        $response['status'] = false;
        $response['message'] = "Missing user_id, gym_id, or rating parameter";
        $status = 400;
    } else {
        $userId = $_POST['user_id'];
        $gymId = $_POST['gym_id'];
        $rating = $_POST['rating'];

        // Check if the user is subscribed to the gym in the user_payments table
        $checkSubscriptionQuery = "SELECT COUNT(*) as count FROM user_payments WHERE user_id = ? AND gym_id = ?";
        $stmt = mysqli_prepare($con, $checkSubscriptionQuery);
        mysqli_stmt_bind_param($stmt, 'ii', $userId, $gymId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $subscriptionCount = mysqli_fetch_assoc($result)['count'];

        if ($subscriptionCount > 0) {
            // User is subscribed to the gym, proceed with rating
            $insertRatingQuery = "INSERT INTO ratings (user_id, gym_id, rating) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($con, $insertRatingQuery);
            mysqli_stmt_bind_param($stmt, 'iii', $userId, $gymId, $rating);
            mysqli_stmt_execute($stmt);

            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $response['status'] = true;
                $response['message'] = "Rating added successfully";
                $status = 200;
            } else {
                $response['status'] = false;
                $response['message'] = "Failed to add rating";
                $status = 500;
            }
        } else {
            $response['status'] = false;
            $response['message'] = "User is not subscribed to the gym";
            $status = 403;
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
