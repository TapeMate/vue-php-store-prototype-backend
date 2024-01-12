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

    public function createWishList()
    {
        parent::setWishList($this->uid, $this->productId);
        return ["success" => true, "message" => "Item added to wishlist"];
    }

}