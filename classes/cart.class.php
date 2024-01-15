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
            // error_log("item: " . join(", ", $item["product_id"]));
            $productId = $item['product_id'];
            $orderAmount = $item['product_order_amount'];

            if (array_key_exists($productId, $mappedExistingItems)) {
                // Update logic
                $updateSql = "UPDATE shoppingcart_items SET product_order_amount = ? WHERE shoppingcart_id = ? AND products_product_id = ?;";
                $updateStmt = $pdo->prepare($updateSql);
                $updateStmt->execute([$orderAmount, $cartId, $productId]);
            } else {
                // Insert logic
                $insertSql = "INSERT INTO shoppingcart_items (shoppingcart_id, products_product_id, product_order_amount) VALUES (?, ?, ?);";
                $insertStmt = $pdo->prepare($insertSql);
                $insertStmt->execute([$cartId, $productId, $orderAmount]);
            }
        }

        return ["success" => true, "message" => "Cart has been updated"];
    }

    public function getCartItems($cartId)
    {
        $sql = "SELECT products_product_id, product_order_amount FROM shoppingcart_items WHERE shoppingcart_id = ?;";
        $stmt = parent::connect()->prepare($sql);
        $stmt->execute([$cartId]);
        $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$cartItems) {
            error_log("Error in getCartItems: " . join(", ", $stmt->errorInfo()));
        }

        return $cartItems;
    }

    public function updateItemOrderAmount($newAmount, $cartId, $productId)
    {
        $sql = "UPDATE shoppingcart_items SET product_order_amount = ? WHERE shoppingcart_id = ? AND products_product_id = ?;";
        $stmt = parent::connect()->prepare($sql);
        if (!$stmt->execute([$newAmount, $cartId, $productId])) {
            error_log("" . join("Error on updating cart Item, ", $stmt->errorInfo()));
        }
        return ["message" => "update successful"];
    }

    public function getCartItemData($cartItems)
    {
        $cartData = [];
        foreach ($cartItems as $item) {
            $productId = $item["products_product_id"];
            $orderAmount = $item["product_order_amount"];

            // fetch Product details
            $productDetails = $this->getProductDetails($productId);

            // add order amount
            $productDetails['product_order_amount'] = $orderAmount;

            // add to array
            $cartData[] = $productDetails;
        }

        return $cartData;
    }

    private function getProductDetails($productId)
    {
        $sql = "SELECT * FROM products WHERE product_id = ?;";
        $stmt = parent::connect()->prepare($sql);
        $stmt->execute([$productId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
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