<?php

class ProductContr extends Product
{
    private $product;
    private $products;

    public function getProductsForFrontend()
    {
        return parent::getProductsFromDB();
    }
}