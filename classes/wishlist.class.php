<?php

class WishList extends Dbh
{
    protected function setWishList($uid, $productId)
    {
        $sql = "INSERT INTO wishlists (users_user_id, products_product_id) VALUES (?,?);";
        $stmt = parent::connect()->prepare($sql);
        $stmt->execute([$uid, $productId]);
    }
}