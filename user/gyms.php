<?php
require '../core/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['user_id'])) {
        $response['status'] = false;
        $response['message'] = "Missing user_id parameter";
        $status = 400;
    } else {
        $userID = $_GET['user_id'];

        $query = "SELECT gyms.id, gyms.name, gyms.sessions, gyms.gender, gyms.address, gyms.lat, gyms.loong, gyms.img, gyms.fees, AVG(ratings.rating) AS avg_rating
                  FROM gyms
                  INNER JOIN user_payments ON gyms.id = user_payments.gym_id
                  LEFT JOIN ratings ON gyms.id = ratings.gym_id
                  WHERE user_payments.user_id = ?
                  GROUP BY gyms.id";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'i', $userID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $gyms = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $row['lat'] = (float) $row['lat'];
            $row['loong'] = (float) $row['loong'];
            $row['img'] = $appPath.'/uploads/gyms/'.$row['img'];
            $row['rating'] = ($row['avg_rating'] !== null) ? round($row['avg_rating'], 2) : null;
            unset($row['avg_rating']);
            $gyms[] = $row;
        }

        $response['status'] = true;
        $response['message'] = "Gyms fetched successfully";
        $response['data'] = $gyms;
        $status = 200;
    }
} else {
    $response['message'] = "Invalid request method";
    $status = 404;
}

echo json_encode($response);
http_response_code($status);
?>
