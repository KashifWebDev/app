<?php
require '../core/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming the video ID is passed in the request body
    $videoId = $_POST['video_id'] ?? null;

    if ($videoId === null) {
        $response['status'] = false;
        $response['message'] = "Missing video_id parameter";
        $status = 400;
    } else {
        // Check if the video exists in the database
        $checkQuery = "SELECT * FROM videos WHERE id = ?";
        $stmt = mysqli_prepare($con, $checkQuery);
        mysqli_stmt_bind_param($stmt, 'i', $videoId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            // Delete the video from the database
            $deleteQuery = "DELETE FROM videos WHERE id = ?";
            $stmt = mysqli_prepare($con, $deleteQuery);
            mysqli_stmt_bind_param($stmt, 'i', $videoId);
            mysqli_stmt_execute($stmt);

            if (mysqli_stmt_affected_rows($stmt) > 0) {
                // Video deleted successfully
                $response['status'] = true;
                $response['message'] = "Video deleted successfully";
                $status = 200;
            } else {
                // Failed to delete video
                $response['status'] = false;
                $response['message'] = "Failed to delete video";
                $status = 500;
            }
        } else {
            // Video not found
            $response['status'] = false;
            $response['message'] = "Video not found";
            $status = 404;
        }

        mysqli_stmt_close($stmt);
    }
} else {
    $response['status'] = false;
    $response['message'] = "Invalid request method";
    $status = 404;
}

echo json_encode($response);
http_response_code($status);
