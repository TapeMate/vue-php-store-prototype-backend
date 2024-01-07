<?php
ini_set('display_errors', '0'); // Turn off error displaying
error_reporting(E_ALL); // Report all errors

class Product extends Dbh
{
    protected function queryProducts()
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