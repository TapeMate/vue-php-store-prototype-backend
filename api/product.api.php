<?php
ini_set('display_errors', '0'); // Turn off error displaying
error_reporting(E_ALL); // Report all errors

// Handle CORS
header('Access-Control-Allow-Origin: *'); // Allows all origins. For production, replace * with your actual domain.
header('Access-Control-Allow-Methods: GET'); // Adjust methods as needed
header('Access-Control-Allow-Headers: Content-Type'); // Adjust headers as needed

// Handle preflight request for OPTIONS method from browser
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit();
}

include_once "../classes/dbh.class.php";
include_once "../classes/product.class.php";
include_once "../classes/product-contr.class.php";
$products = new ProductContr();

$result = $products->getProductData();
echo json_encode($result);
