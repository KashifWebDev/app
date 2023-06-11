<?php
require '../core/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requiredParameters = ['gym_id', 'gymName', 'sessionType', 'gender', 'address', 'lat', 'long', 'fee'];
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
        $gymName = $_POST['gymName'];
        $sessionType = $_POST['sessionType'];
        $gender = $_POST['gender'];
        $address = $_POST['address'];
        $lat = $_POST['lat'];
        $loong = $_POST['long'];
        $fees = $_POST['fee'];

        // Handle file upload
        $uploadDir = '../uploads/gyms/'; // Specify the directory where you want to store the uploaded files
        $img = '';

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

        $updateQuery = "UPDATE gyms SET name = '$gymName', sessions = '$sessionType', gender = '$gender', address = '$address', lat = $lat, loong = $loong, fees = $fees";

        if (!empty($img)) {
            $updateQuery .= ", img = '$img'";
        }

        $updateQuery .= " WHERE id = $gymId";

        $result = mysqli_query($con, $updateQuery);

        if ($result) {
            $response['status'] = true;
            $response['message'] = "Gym updated successfully";
            $status = 200;
        } else {
            $response['message'] = "Failed to update gym";
            $status = 404;
        }
    }
} else {
    $response['message'] = "Invalid request method";
    $status = 404;
}

echo json_encode($response);
http_response_code($status);
