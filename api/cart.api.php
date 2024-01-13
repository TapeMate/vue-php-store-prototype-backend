<?php

// Handle CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Include classes
include_once "../classes/dbh.class.php";
include_once "../classes/cart.class.php";
include_once "../classes/cart-contr.class.php";
// Handle preflight request for OPTIONS method from browser
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $product = $data["product"];
    $amount = $data["amount"];

    if ($product->product_id == 1 && $amount == 1) {
        $response = ["success" => true];
        echo json_encode($response);
    }
}