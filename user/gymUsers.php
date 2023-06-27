<?php
require '../core/app.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['gym_id'])) {
        $response['status'] = false;
        $response['message'] = "Missing gym_id parameter";
        $status = 400;
    } else {
        $gymID = $_GET['gym_id'];

        // Get user IDs associated with the gym from user_payments table
        $query = "SELECT user_id FROM user_payments WHERE gym_id = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'i', $gymID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            $userIDs = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $userIDs[] = $row['user_id'];
            }

            if (!empty($userIDs)) {
                // Get user objects for the associated user IDs from users table
                $userQuery = "SELECT id, userType, fullName, email, phone, address, verified, lat, loong, verification_code FROM users WHERE id IN (" . implode(",", $userIDs) . ")";
                $userResult = mysqli_query($con, $userQuery);

                if ($userResult) {
                    $users = [];
                    while ($userRow = mysqli_fetch_assoc($userResult)) {
                        $users[] = $userRow;
                    }

                    $response['status'] = true;
                    $response['message'] = "Users fetched successfully";
                    $response['data'] = $users;
                    $status = 200;
                } else {
                    $response['status'] = false;
                    $response['message'] = "Failed to fetch users";
                    $status = 500;
                }
            } else {
                $response['status'] = false;
                $response['message'] = "No users found for the provided gym ID";
                $status = 404;
            }
        } else {
            $response['status'] = false;
            $response['message'] = "Failed to fetch user IDs";
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
