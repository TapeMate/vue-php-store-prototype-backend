<?php

class ProductContr extends Product
{
    private $product;
    private $products;

    public function getProductsForFrontend()
    {
        parent::getProductsFromDB();
        return ["message" => "received products."];
    }
}