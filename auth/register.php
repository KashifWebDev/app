<?php
require '../core/app.php';

if(isset($_POST['userType']) && isset($_POST['fullName']) && isset($_POST['email']) &&
    isset($_POST['phone']) && isset($_POST['address']) && isset($_POST['password'])){
    $userType = filter_input(INPUT_POST, 'userType');
    $fullName = filter_input(INPUT_POST, 'fullName');
    $email = filter_input(INPUT_POST, 'email');
    $phone = filter_input(INPUT_POST, 'phone');
    $address = filter_input(INPUT_POST, 'address');
    $password = filter_input(INPUT_POST, 'password');

    $duplicate=mysqli_query($con,"select * from users where email='$email'");
    if (mysqli_num_rows($duplicate)>0)
    {
        $response['message'] = 'Email Already Exists!';
        $status = 404;
    }else{
        $query = "INSERT INTO users (userType, fullName, email, phone, address, password)
                VALUES ('$userType', '$fullName', '$email', '$phone', '$address', '$password')";
        $result = mysqli_query($con, $query);
        if($result){
            $response['status'] = true;
            $response['message'] = "Registration Successful";
            $status = 200;
        }else{
            $response['message'] = mysqli_error($con);
            $status = 500;
        }
    }
}else{
    $response['message'] = "Must Pass required fields";
    $status = 500;
}

echo json_encode($response);
http_response_code($status);
?>