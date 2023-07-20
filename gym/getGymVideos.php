<?php
require '../core/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['gym_id'])) {
        $response['status'] = false;
        $response['message'] = "Missing gym_id parameter";
        $status = 400;
    } else {
        $gymID = $_GET['gym_id'];

        // Fetch videos for the specified gym
        $query = "SELECT * FROM videos WHERE gym_id = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'i', $gymID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            $videos = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $video = array(
                    'id' => $row['id'],
                    'path' => $appPath.'/uploads/videos/'.$row['filename']
                );
                array_push($videos, $video);
            }

//            print_r($innerArray); exit(); die();

            $response['status'] = true;
            $response['message'] = "Videos fetched successfully";
            $response['data'] =  $videos;
            $status = 200;
        } else {
            $response['status'] = false;
            $response['message'] = "Failed to fetch videos";
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
