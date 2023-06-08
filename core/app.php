<?php
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

$hostname = "localhost";
$user = "u953547654_app";
$pwd = "App@123455";
$db = "u953547654_app";
$con = mysqli_connect($hostname, $user, $pwd, $db);
echo "Test";
exit();

$response = array(
    'status' => false,
    'message' => '',
    'data' => array()
);
if ($con->connect_errno) {
    echo "Failed to connect to MySQL: " . $con->connect_error;
    exit();
}