<?php

require '../core/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requiredParameters = ['user_id'];
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
        $userId = $_POST['user_id'];

        // Check if the user exists
        $checkUserQuery = "SELECT id FROM users WHERE id = $userId";
        $result = mysqli_query($con, $checkUserQuery);

        if ($result && mysqli_num_rows($result) > 0) {
            // Handle file upload
            $uploadDir = '../uploads/users/';
            $img = 'default.jpg';

            if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['img'];
                $fileName = $file['name'];
                $fileTmpName = $file['tmp_name'];

                // Generate a unique file name to avoid conflicts
                $uniqueFileName = uniqid() . '_' . $fileName;

                // Move the uploaded file to the desired directory
                if (move_uploaded_file($fileTmpName, $uploadDir . $uniqueFileName)) {
                    $img = $uniqueFileName;
                }
            }

            // Update the user's profile image
            $updateQuery = "UPDATE users SET img = '$img' WHERE id = $userId";
            $result = mysqli_query($con, $updateQuery);

            if ($result) {
                $response['status'] = true;
                $response['message'] = "User profile image updated successfully";
                $response['data'] = ['img' => $appPath . '/uploads/users/' . $img];
                $status = 200;
            } else {
                $response['message'] = "Failed to update user profile image";
                $status = 404;
            }
        } else {
            $response['status'] = false;
            $response['message'] = "User not found";
            $status = 404;
        }
    }
} else {
    $response['message'] = "Invalid request method";
    $status = 404;
}

echo json_encode($response);
http_response_code($status);
