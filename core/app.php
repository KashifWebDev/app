<?php
header("Content-Type: application/json");
echo "Test";
exit();

$hostname = "localhost";
$user = "u953547654_app";
$pwd = "App@123455";
$db = "u953547654_app";
$con = mysqli_connect($hostname, $user, $pwd, $db);

$response = array(
    'status' => false,
    'message' => '',
    'data' => array()
);
if ($con->connect_errno) {
    echo "Failed to connect to MySQL: " . $con->connect_error;
    exit();
}