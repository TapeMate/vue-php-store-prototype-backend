<?php

class Product extends Dbh
{
    protected function getProductFromDB()
    {
        $sql = "SELECT * FROM products WHERE product_id >= 1;";
        $stmt = parent::connect()->prepare($sql);

        if (!$stmt->execute()) {
            echo json_encode(["error" => "no products found."]);
        }
    }
}