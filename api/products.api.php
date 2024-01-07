<?php

// Handle CORS
header('Access-Control-Allow-Origin: *'); // Allows all origins. For production, replace * with your actual domain.
header('Access-Control-Allow-Methods: POST'); // Adjust methods as needed
header('Access-Control-Allow-Headers: Content-Type'); // Adjust headers as needed

// Handle preflight request for OPTIONS method from browser
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit();
}

include_once "../classes/dbh.class.php";
include_once "../classes/products.class.php";
include_once "../classes/products-contr.class.php";
$products = new ProductContr();

$result = $products->getProductsForFrontend();
echo json_encode($result);
