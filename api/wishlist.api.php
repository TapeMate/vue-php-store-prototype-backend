<?php
// Handle CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight request for OPTIONS method from browser
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit();
}

//decode JSON string into a PHP associative array
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $wishlistData = json_decode(file_get_contents('php://input'), true);

    // Debug: Output $loginData to a log file to see user data
    file_put_contents('debug.log', print_r($wishlistData, true));

    // echo json_encode($wishlistData);
    echo json_encode(["success" => true, "message" => "Item added to wishlist"]);
}

