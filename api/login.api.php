<?php
ini_set('display_errors', '0'); // Turn off error displaying
error_reporting(E_ALL); // Report all errors

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

    $loginData = json_decode(file_get_contents('php://input'), true);

    // see Video from Dani Krossing regarding error handling and logging to possibly make some changes
    // Debug: Output $loginData to a log file to see user data
    file_put_contents('debug.log', print_r($loginData, true));

    // Now you can use $loginData just like you would use $_POST
    $uid = $loginData['uid'];
    $pwd = $loginData['pwd'];

    // Instantiate SignupContr class and include classes
    include_once "../classes/dbh.class.php";
    include_once "../classes/login.class.php";
    include_once "../classes/login-contr.class.php";
    $login = new LoginContr($uid, $pwd);

    // call method from Controller
    // store in $response variable vor JS encoding later
    $response = $login->loginUser();

    // encode back to JSON for JS response
    echo json_encode($response);
} else {
    // Handle error: Not a POST request
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Only POST method is allowed"]);
}