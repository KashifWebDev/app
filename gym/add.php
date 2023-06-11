<?php
require '../core/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requiredParameters = ['user_id', 'gymName', 'sessionType', 'gender', 'address', 'lat', 'long', 'fee'];
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
        $gymName = $_POST['gymName'];
        $sessionType = $_POST['sessionType'];
        $gender = $_POST['gender'];
        $address = $_POST['address'];
        $lat = $_POST['lat'];
        $loong = $_POST['long'];
        $fees = $_POST['fee'];

        // Handle file upload
        $uploadDir = '../uploads/gyms/'; // Specify the directory where you want to store the uploaded files
        $img = 'default.jpg';

        if (isset($_FILES['img'])) {
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

        // Insert parameters into the 'gyms' table
        $query = "INSERT INTO gyms (user_id, name, sessions, gender, address, lat, loong, img, fees) VALUES
                        ($userId, '$gymName', '$sessionType', '$gender', '$address', $lat, $loong, '$img', '$fees')";
        $stmt = mysqli_query($con, $query);

        if ($stmt) {
            $response['status'] = true;
            $response['message'] = "Gym inserted successfully";
            $status = 200;
        } else {
            $response['message'] = "Failed to insert gym";
            $status = 404;
        }
    }
} else {
    $response['message'] = "Invalid request method";
    $status = 404;
}

echo json_encode($response);
http_response_code($status);
