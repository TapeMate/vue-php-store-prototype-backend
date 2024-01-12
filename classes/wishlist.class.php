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
}