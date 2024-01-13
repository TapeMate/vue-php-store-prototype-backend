<?php

class WishList extends Dbh
{
    public function createWishList($uid)
    {
        $pdo = parent::connect();
        $sql = "INSERT INTO wishlists (users_user_id) VALUES (?);";
        $stmt = $pdo->prepare($sql);

        if (!$stmt->execute([$uid])) {
            error_log("Error in createWishList: " . join(', ', $pdo->errorInfo()));
            return false;
        }

        return $pdo->lastInsertId();
    }

    public function addWishListItem($wishListId, $productId)
    {
        $sql = "INSERT INTO wishlist_items (wishlist_id, products_product_id) VALUE (?, ?);";
        $stmt = parent::connect()->prepare($sql);
        $stmt->execute([$wishListId, $productId]);
    }

    protected function checkWishListExists($uid)
    {
        $sql = "SELECT users_user_id FROM wishlists WHERE users_user_id = ?";
        $stmt = parent::connect()->prepare($sql);

        if (!$stmt->execute([$uid])) {
            error_log("Error in checkWishListExists: " . join(', ', $stmt->errorInfo()));
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

    protected function checkItemOnWishList($wishListId, $productId)
    {
        $sql = "SELECT products_product_id FROM wishlist_items WHERE wishlist_id = ? AND products_product_id = ?;";
        $stmt = parent::connect()->prepare($sql);
        $stmt->execute([$wishListId, $productId]);

        $result = null;

        if ($stmt->rowCount() == 0) {
            $result = false;
        } else if ($stmt->rowCount() == 1) {
            $result = true;
        }

        return $result;
    }

    protected function getWishListId($uid)
    {
        $sql = "SELECT wishlist_id FROM wishlists WHERE users_user_id = (?);";
        $stmt = parent::connect()->prepare($sql);
        $stmt->execute([$uid]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return ["error" => "Wishlist not found"];
        }

        return $result["wishlist_id"];
    }

    protected function getWishListItemIDs($wishListId)
    {
        $sql = "SELECT products_product_id FROM wishlist_items WHERE wishlist_id = (?);";
        $stmt = parent::connect()->prepare($sql);
        $stmt->execute([$wishListId]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$items) {
            return ["error" => "Wishlist Items not found"];
        }

        $productIds = array_column($items, "products_product_id");
        return ["products_product_id" => $productIds];
    }

    protected function getWishListItemData($productIds)
    {
        // transform into regular array
        $productIdsArray = $productIds["products_product_id"];
        // implode array values to string and create placeholders for every entry 
        $placeholders = implode(',', array_fill(0, count($productIdsArray), '?'));
        $sql = "SELECT * FROM products WHERE product_id IN ($placeholders);";
        $stmt = parent::connect()->prepare($sql);

        if (!$stmt->execute($productIdsArray)) {
            return ["error" => "Database query failed"];
        }

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ["wishListData" => $data];
    }

    protected function deleteItem($wishListId, $productId)
    {
        $sql = "DELETE FROM wishlist_items WHERE wishlist_id = ? AND products_product_id = ?;";
        $stmt = parent::connect()->prepare($sql);

        if (!$stmt->execute([$wishListId, $productId])) {
            return ["error" => "Database deletion failed"];
        }
    }
}