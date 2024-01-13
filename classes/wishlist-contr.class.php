<?php

class WishListContr extends WishList
{
    private $uid;
    private $productId;

    public function __construct($uid, $productId)
    {
        $this->uid = $uid;
        $this->productId = $productId;
    }

    public function setWishList()
    {
        if ($this->wishListExists() == false) {
            $wishListId = parent::createWishList($this->uid);
            parent::addWishListItem($wishListId, $this->productId);
            return ["success" => true, "message" => "Created wishlist & added item"];
        }

        $wishListId = parent::getWishListId($this->uid);
        parent::addWishListItem($wishListId, $this->productId);
        return ["success" => true, "message" => "Item added to wishlist"];
    }

    public function getWishList()
    {
        if ($this->wishListExists() == false) {
            return ["success" => false, "error" => "Wishlist does not exist"];
        }
        $wishListId = parent::getWishListId($this->uid);

        if (isset($wishListId['error'])) {
            return ["success" => false, "error" => $wishListId["error"]];
        }

        $productIds = parent::getWishListItems($wishListId);
        if (isset($productIds['error'])) {
            return ["success" => false, "error" => $productIds["error"]];
        }

        return ["success" => true, "products_product_id" => $productIds];
    }

    private function wishListExists()
    {
        $result = null;
        if (!parent::checkWishListExists($this->uid)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }
}