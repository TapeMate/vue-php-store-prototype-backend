<?php

class Cart extends Dbh
{
    public function createCart($uid)
    {
        $pdo = parent::connect();
        $sql = "INSERT INTO shoppingcarts (users_user_id) VALUES (?);";
        $stmt = $pdo->prepare($sql);

        if (!$stmt->execute([$uid])) {
            error_log("Error in createCart: " . join(", ", $pdo->errorInfo()));
            return false;
        }

        return $pdo->lastInsertId();
    }

    public function addItemsToCart($cartId, $items)
    {
        $pdo = parent::connect();
        $sql = "INSERT INTO shoppingcart_items (shoppingcart_id, products_product_id, product_order_amount) VALUES (?,?,?);";
        $stmt = $pdo->prepare($sql);

        foreach ($items as $item) {
            $productId = $item["product_id"];
            $orderAmount = $item["product_order_amount"];

            if (!$stmt->execute([$cartId, $productId, $orderAmount])) {
                error_log("error in forEach: " . join(", ", $pdo->errorInfo()));
            }
        }
        return ["success" => true, "message" => "Items added to cart"];
    }

    public function findCart($uid)
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

    public function updateItemsOnCart($cartId, $items)
    {
        $pdo = parent::connect();
        $sql = "SELECT * FROM shoppingcart_items WHERE shoppingcart_id = ?;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$cartId]);
        $existingItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $mappedExistingItems = [];
        foreach ($existingItems as $item) {
            $mappedExistingItems[$item["products_product_id"]] = $item;
        }

        foreach ($items as $item) {
            $productId = $item['product_id'];
            $orderAmount = $item['order_amount'];

            if (array_key_exists($productId, $mappedExistingItems)) {
                // Update logic
                $updateSql = "UPDATE shoppingcart_items SET product_order_amount = ? WHERE shoppingcart_id = ? AND products_product_id = ?";
                $updateStmt = $pdo->prepare($updateSql);
                $updateStmt->execute([$orderAmount, $cartId, $productId]);
            } else {
                // Insert logic
                $insertSql = "INSERT INTO shoppingcart_items (shoppingcart_id, products_product_id, product_order_amount) VALUES (?, ?, ?)";
                $insertStmt = $pdo->prepare($insertSql);
                $insertStmt->execute([$cartId, $productId, $orderAmount]);
            }
        }

        return ["success" => true, "message" => "Cart has been updated"];
    }

    public function checkCartExists($uid)
    {
        $sql = "SELECT users_user_id FROM shoppingcarts WHERE users_user_id = ?;";
        $stmt = parent::connect()->prepare($sql);

        if (!$stmt->execute([$uid])) {
            error_log("Error in checkCartExists: " . join(", ", $stmt->errorInfo()));
            exit();
        }

        $checkResult = null;
        if ($stmt->rowCount() > 0) {
            $checkResult = true;
        } else {
            $checkResult = false;
        }

        return $checkResult;
    }
}