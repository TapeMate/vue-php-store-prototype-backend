<?php
ini_set('display_errors', '0'); // Turn off error displaying
error_reporting(E_ALL); // Report all errors

class ProductContr extends Product
{
    private $product;
    private $products;

    public function getProductData()
    {
        return parent::queryProducts();
    }
}