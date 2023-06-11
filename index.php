<?php
require 'core/app.php';

$response['status'] = false;
$response['message'] = "Invalid Path";
$status = 400;

echo json_encode($response);
http_response_code($status);
?>