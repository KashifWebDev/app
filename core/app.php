<?php
header("Content-Type: application/json");

$hostname = "localhost";
$user = "u953547654_app";
$pwd = "App@12345";
$db = "u953547654_app";
$con = mysqli_connect($hostname, $user, $pwd, $db);

$response = array(
    'status' => false,
    'message' => '',
    'data' => array()
);
if ($con -> connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
    exit();
}
