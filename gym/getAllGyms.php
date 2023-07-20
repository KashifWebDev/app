<?php
require '../core/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['user_id'])) {
        $response['status'] = false;
        $response['message'] = "Missing user_id parameter";
        $status = 400;
    } else {
        $userId = $_GET['user_id'];
        $query = "SELECT gyms.id, gyms.user_id, gyms.name, gyms.sessions, gyms.gender, gyms.address, gyms.fees, gyms.lat, gyms.loong, gyms.days, gyms.startTime, gyms.endTime, gyms.img, gyms.types, AVG(ratings.rating) AS avg_rating
                FROM gyms
                LEFT JOIN ratings ON gyms.id = ratings.gym_id
                WHERE gyms.user_id = '$userId'
                GROUP BY gyms.id";
        $result = mysqli_query($con, $query);

        if ($result) {
            $gyms = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $gymId = $row['id'];

                // Fetch videos for the current gym
                $videosQuery = "SELECT filename FROM videos WHERE gym_id = '$gymId'";
                $videosResult = mysqli_query($con, $videosQuery);
                $videos = array();
                while ($videoRow = mysqli_fetch_assoc($videosResult)) {
                    $videos[] = $appPath.'/uploads/videos/'.$videoRow['filename'];
                }

                $gyms[] = array(
                    'id' => $row['id'],
                    'user_id' => $row['user_id'],
                    'name' => $row['name'],
                    'sessions' => $row['sessions'],
                    'gender' => $row['gender'],
                    'address' => $row['address'],
                    'fee' => (int) $row['fees'],
                    'lat' => (float) $row['lat'],
                    'loong' => (float) $row['loong'],
                    'days' => $row['days'],
                    'startTime' => $row['startTime'],
                    'endTime' => $row['endTime'],
                    'types' => $row['types'],
                    'img' => $appPath.'/uploads/gyms/'.$row['img'],
                    'videos' => $videos,
                    'rating' => ($row['avg_rating'] !== null) ? round($row['avg_rating'], 2) : null
                );
            }

            $response['status'] = true;
            $response['message'] = "Gyms fetched successfully";
            $response['data'] = $gyms;
            $status = 200;
        } else {
            $response['message'] = "Failed to fetch gyms";
            $status = 404;
        }
    }
} else {
    $response['message'] = "Invalid request method";
    $status = 404;
}

echo json_encode($response);
http_response_code($status);
