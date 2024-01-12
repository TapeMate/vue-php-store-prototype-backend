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
            return ["success" => true, "message" => "Item added to wishlist"];
        }

        $wishListId = parent::getWishListId($this->uid);
        // file_put_contents('debug.log', print_r($wishListId, true));
        parent::addWishListItem($wishListId, $this->productId);
        return ["success" => true, "message" => "Item added to wishlist"];
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