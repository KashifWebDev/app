<?php
require '../core/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming the form field name for the video file is 'video'
    $videoFile = $_FILES['video'] ?? null;

    if ($videoFile === null || $videoFile['error'] !== UPLOAD_ERR_OK) {
        // Video file not found or upload error occurred
        $response['status'] = false;
        $response['message'] = "Failed to upload video";
        $status = 400;
    } else {
        // Generate a unique filename for the video
        $filename = generateUniqueFilename($videoFile['name']);
        $targetFilePath = '../uploads/videos/' . $filename;

        // Move the uploaded video file to the target directory
        if (move_uploaded_file($videoFile['tmp_name'], $targetFilePath)) {
            // Store the video file information in the database
            $uploadedBy = $_POST['gym_id']; // Assuming the trainer ID is stored in the session

            $insertQuery = "INSERT INTO videos (filename, gym_id, upload_date)
                            VALUES (?, ?, NOW())";
            $stmt = mysqli_prepare($con, $insertQuery);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 'si', $filename, $uploadedBy);
                mysqli_stmt_execute($stmt);

                // Check if the insertion was successful
                if (mysqli_stmt_affected_rows($stmt) > 0) {
                    $response['status'] = true;
                    $response['message'] = "Video uploaded successfully";
                    $status = 200;
                } else {
                    $response['status'] = false;
                    $response['message'] = "Failed to upload video";
                    $status = 500;
                }

                mysqli_stmt_close($stmt);
            } else {
                $response['status'] = false;
                $response['message'] = "Failed to prepare SQL statement";
                $status = 500;
            }
        } else {
            $response['status'] = false;
            $response['message'] = "Failed to move video file";
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

function generateUniqueFilename($originalFilename)
{
    $extension = pathinfo($originalFilename, PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $extension;
    return $filename;
}
