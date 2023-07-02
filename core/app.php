<?php

header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);
const APIKEY = 'AIzaSyCJBr0IOmZ5jL8vtfNK30gVzpd_fGc1xm4';

$hostname = "localhost";
$user = "root";
$pwd = "";
$db = "gymApp";

$user = "u953547654_app";
$pwd = "App@12345";
$db = "u953547654_app";



$appPath = $GLOBALS['appPath'] = 'https://app.kashifali.me';


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
