<?php
header("Content-Type: application/json");

$hostname = "localhost";
$user = "root";
$pwd = "";
$db = "projects_gymapp";
$con = mysqli_connect($hostname, $user, $pwd, $db);

$response = array(
    'status' => false,
    'message' => '',
    'data' => array()
);

