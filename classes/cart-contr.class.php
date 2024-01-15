<?php

class CartContr extends Cart
{
    private $uid;
    private $items;


    public function __construct($uid, $items)
    {

        $this->uid = $uid;
        $this->items = $items;
    }

    public function setCart()
    {
        if ($this->cartExists() == false) {
            $cartId = parent::createCart($this->uid);
            parent::addItemsToCart($cartId, $this->items);
            return ["success" => true, "message" => "Created cart & added items"];
        } else {
            $cartId = parent::findCart($this->uid);
            parent::updateItemsOnCart($cartId, $this->items);
            return ["success" => true, "message" => "Items added to cart"];
        }
    }

    public function getCart()
    {
        if ($this->cartExists() == false) {
            return ["success" => true, "message" => "No cart found"];
        } else {
            $cartId = parent::findCart($this->uid);
            $cartItems = parent::getCartItems($cartId);
            $cartData = parent::getCartItemData($cartItems);
            return ["success" => true, "data" => $cartData];
        }
    }

    public function updateCartItem($productId, $newAmount)
    {
        $cartId = parent::findCart($this->uid);
        parent::updateItemOrderAmount($newAmount, $cartId, $productId);
        return ["success" => true, "message" => "Cart item updated"];
    }

    public function cartExists()
    {
        $result = null;
        if (!parent::checkCartExists($this->uid)) {
            $result = false;
        } else {
            $result = true;
        }
        return $result;
    }
}