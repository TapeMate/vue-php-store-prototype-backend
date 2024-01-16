<?php

// Handle CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Include classes
include_once "../classes/dbh.class.php";
include_once "../classes/order.class.php";
include_once "../classes/order-contr.class.php";
// Handle preflight request for OPTIONS method from browser
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $uid = $data["uid"];
    $items = $data["items"];

    $order = new OrderContr($uid, $items);
    $order->placeOrder();

    echo json_encode(["success" => true]);
}