<?php

// Handle CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, DELETE');
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
    if ($_SERVER["PATH_INFO"] == "/cart/push") {
        $data = json_decode(file_get_contents("php://input"), true);
        $uid = $data["userId"];
        $localCart = $data["localCart"];

        $newCart = new CartContr($uid, $localCart);

        $response = $newCart->setCart();
        // $response = ["success" => true];
        echo json_encode($response);

    } else if ($_SERVER["PATH_INFO"] == "/cart/update") {
        $data = json_decode(file_get_contents("php://input"), true);
        $productId = $data["item"]["product_id"];
        $newAmount = $data["amount"];
        $uid = $data["uid"];

        // error_log("Incoming data: " . $data["amount"]);

        $cartUpdate = new CartContr($uid, null);
        $cartUpdate->updateCartItem($productId, $newAmount);

        echo json_encode(["success" => true]);

    }


} else if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $uid = isset($_GET['userId']) ? $_GET['userId'] : null;

    if ($uid === null) {
        echo json_encode(["error" => "User ID is required"]);
        exit();
    }

    $cart = new CartContr($uid, null);

    $response = $cart->getCart();
    echo json_encode($response);

} else if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    $data = json_decode(file_get_contents("php://input"), true);
    $productId = $data["item"]["product_id"];
    $uid = $data["uid"];

    $cartDelete = new CartContr($uid, null);
    $cartDelete->deleteCartItem($productId);

    echo json_encode(["success" => true]);
} else {
    // Handle error: Not a POST request
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Only POST or GET method is allowed"]);
}