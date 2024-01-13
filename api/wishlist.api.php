<?php
// Handle CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, DELETE');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Include classes
include_once "../classes/dbh.class.php";
include_once "../classes/wishlist.class.php";
include_once "../classes/wishlist-contr.class.php";
// Handle preflight request for OPTIONS method from browser
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $uid = $data['userId'];
    $productId = $data['productId'];

    $wishListItem = new WishListContr($uid, $productId);

    $response = $wishListItem->setWishList();
    echo json_encode($response);

} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $uid = isset($_GET['userId']) ? $_GET['userId'] : null;

    if ($uid === null) {
        echo json_encode(["error" => "User ID is required"]);
        exit();
    }
    $wishListItem = new WishListContr($uid, null);

    $response = $wishListItem->getWishList();
    echo json_encode($response);

} else if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    // $data = json_decode(file_get_contents("php://input"), true);
    // $uid = $data["userId"];
    // $productId = $data["productId"];

    // if ($uid = 30 && $productId == 1) {
    //     echo json_encode($response);
    // } else {
    //     exit();
    // }

    $response = ["success" => true];
    echo json_encode($response);

} else {
    // Handle error for unsupported request method
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Only POST and GET methods are allowed"]);
}