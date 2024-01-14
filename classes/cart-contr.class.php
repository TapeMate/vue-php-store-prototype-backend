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