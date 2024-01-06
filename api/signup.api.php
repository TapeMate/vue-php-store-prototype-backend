<?php

// Handle CORS
header('Access-Control-Allow-Origin: *'); // Allows all origins. For production, replace * with your actual domain.
header('Access-Control-Allow-Methods: POST'); // Adjust methods as needed
header('Access-Control-Allow-Headers: Content-Type'); // Adjust headers as needed

// Handle preflight request for OPTIONS method from browser
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit();
}
// decode JSON string into a PHP associative array 
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $signupData = json_decode(file_get_contents('php://input'), true);

    // see Video from Dani Krossing regarding error handling and logging to possibly make some changes
    // Debug: Output $signupData to a log file to see user data
    file_put_contents('debug.log', print_r($signupData, true));

    // Now you can use $signupData just like you would use $_POST
    $uid = $signupData['uid'];
    $pwd = $signupData['pwd'];
    $pwdRepeat = $signupData['pwdRepeat'];
    $email = $signupData['email'];

    // Instantiate SignupContr class and include classes
    include_once "../classes/dbh.class.php";
    include_once "../classes/signup.class.php";
    include_once "../classes/signup-contr.class.php";
    $signup = new SignupContr($uid, $pwd, $pwdRepeat, $email);

    // call method from Controller
    // store in $response variable vor JS encoding later
    $response = $signup->signupUser();

    // encode back to JSON for JS response
    echo json_encode($response);
} else {
    // Handle error: Not a POST request
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Only POST method is allowed"]);
}