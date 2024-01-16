<?php

class Order extends Dbh
{
    public function createOrder($uid)
    {
        $pdo = parent::connect();
        $sql = "INSERT INTO orders (users_user_id) VALUES (?);";
        $stmt = $pdo->prepare($sql);

        if (!$stmt->execute([$uid])) {
            error_log("Error in createOrder: " . join(", ", $pdo->errorInfo()));
            return false;
        }

        return $pdo->lastInsertId();
    }

    public function insertOrderData($orderId, $items)
    {
        $pdo = parent::connect();
        $sql = "INSERT INTO order_items (order_id, products_product_id, product_order_amount) VALUES (?,?,?);";
        $stmt = $pdo->prepare($sql);

        // $itemsString = print_r($items, true);
        // error_log("Items: " . $itemsString);

        foreach ($items as $item) {
            $productId = $item["product_id"];
            $orderAmount = $item["product_order_amount"];

            if (!$stmt->execute([$orderId, $productId, $orderAmount])) {
                error_log("error in adding item to order: " . join(", ", $pdo->errorInfo()));
            }
        }

        return ["success" => true, "message" => "Items added to order"];
    }

    public function selectCart($uid)
    {
        $sql = "SELECT shoppingcart_id FROM shoppingcarts WHERE users_user_id = ?;";
        $stmt = parent::connect()->prepare($sql);
        $stmt->execute([$uid]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            error_log("Error in findCart: " . join(", ", $stmt->errorInfo()));
        }

        return $result["shoppingcart_id"];
    }

    public function dropCartItems($cartId)
    {
        $sql = "DELETE FROM shoppingcart_items WHERE shoppingcart_id = ?;";
        $stmt = parent::connect()->prepare($sql);
        if (!$stmt->execute([$cartId])) {
            error_log("Error in drop cart items" . join(", ", $stmt->errorInfo()));
        }

        return ["success" => true, "message" => "Items deleted"];
    }

    public function dropCart($cartId)
    {
        $sql = "DELETE FROM shoppingcarts WHERE shoppingcart_id = ?;";
        $stmt = parent::connect()->prepare($sql);
        if (!$stmt->execute([$cartId])) {
            error_log("error in drop cart" . join(", ", $stmt->errorInfo()));
        }

        return ["success" => true, "message" => "Items deleted"];
    }

    public function updateItemStockAmount($items)
    {
        foreach ($items as $item) {
            $productId = $item["product_id"];
            $orderAmount = $item["product_order_amount"];
            $stockAmount = $this->getStockAmount($productId);
            $newStockAmount = $stockAmount - $orderAmount;

            $sql = "UPDATE products SET product_stock_amount = ? WHERE product_id = ?;";
            $stmt = parent::connect()->prepare($sql);

            if (!$stmt->execute([$newStockAmount, $productId])) {
                error_log("" . join(", ", $stmt->errorInfo()));
            }
        }

        return ["success" => true, "message" => "Stock amount updated"];
    }

    private function getStockAmount($productId)
    {
        $sql = "SELECT product_stock_amount FROM products WHERE product_id = ?;";
        $stmt = parent::connect()->prepare($sql);
        $stmt->execute([$productId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            error_log("" . join(", ", $stmt->errorInfo()));
        }

        return $result["product_stock_amount"];
    }
}