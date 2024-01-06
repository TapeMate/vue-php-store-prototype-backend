<?php

// Allow CORS
header('Access-Control-Allow-Origin: *'); // Allows all origins. For production, replace * with your actual domain.
header('Access-Control-Allow-Methods: POST'); // Adjust methods as needed
header('Access-Control-Allow-Headers: Content-Type'); // Adjust headers as needed

// Handle preflight request for OPTIONS method from browser
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit();
}

// decode JSON string into a PHP associative array 
header('Content-Type: application/json');
$inputData = json_decode(file_get_contents('php://input'), true);

// see Video from Dani Krossing regarding error handling and logging to possibly make some changes
// Debug: Output $inputData to a log file
file_put_contents('debug.log', print_r($inputData, true));

// Now you can use $inputData just like you would use $_POST
$uid = $inputData['uid'];
$pwd = $inputData['pwd'];
$pwdRepeat = $inputData['pwdRepeat'];
$email = $inputData['email'];

// encode back to JSON for JS response
echo json_encode(["message" => "Signup successful"]);