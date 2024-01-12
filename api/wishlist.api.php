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
    $wishListData = json_decode(file_get_contents('php://input'), true);
    // file_put_contents('debug.log', print_r($wishListData, true));

    $uid = $wishListData['userId'];
    $productId = $wishListData['productId'];

    include_once "../classes/dbh.class.php";
    include_once "../classes/wishlist.class.php";
    include_once "../classes/wishlist-contr.class.php";
    $wishListItem = new WishListContr($uid, $productId);

    $response = $wishListItem->setWishList();

    echo json_encode($response);
} else {
    // Handle error: Not a POST request
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Only POST method is allowed"]);
}