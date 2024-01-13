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

        if ($this->itemOnWishList($wishListId) == true) {
            return ["success" => false, "message" => "Item already on wishlist"];
            // should not happen because should be checked on load on frontend to disable button
        } else {
            parent::addWishListItem($wishListId, $this->productId);
            return ["success" => true, "message" => "Item added to wishlist"];
        }

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

        $productIds = parent::getWishListItemIDs($wishListId);
        if (isset($productIds['error'])) {
            return ["success" => false, "error" => $productIds["error"]];
        }

        $productData = parent::getWishListItemData($productIds);
        if (isset($productData["error"])) {
            return ["success" => false, "error" => $productData["error"]];
        }

        return ["success" => true, "wishListData" => $productData["wishListData"]];
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

    private function itemOnWishList($wishListId)
    {
        $result = null;
        if (!parent::checkItemOnWishList($wishListId, $this->productId)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }
}