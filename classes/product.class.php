<?php

class Product extends Dbh
{
    protected function getProductsFromDB()
    {
        $sql = "SELECT * FROM products WHERE product_id >= 1;";
        $stmt = parent::connect()->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            return ["error" => "No product found."];
        }

        $product = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ["product" => $product];
    }
}