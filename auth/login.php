<?php
require '../core/app.php';

if(isset($_POST['email']) && isset($_POST['password'])){
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password');

    $query = "select id, userType, fullName, email, phone, address from users where email='$email' AND password='$password'";
    $result = mysqli_query($con, $query);
    if($result){
        if(mysqli_num_rows($result) > 0){
            $res = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if($response['verified']){
                $response['status'] = true;
                $response['message'] = "Login Successful";
                $response['data'] = $res;
                $status = 200;
            }else{
                $response['message'] = "Please verify your account in order to proceed.";
                $status = 404;
            }
        }else{
            $response['message'] = "Login failed";
            $status = 404;
        }
    }
}else{
    $response['message'] = "Must Pass required fields";
    $status = 404;
}

echo json_encode($response);
http_response_code($status);
?>