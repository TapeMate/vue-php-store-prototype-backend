<?php

class OrderContr extends Order
{
    private $uid;
    private $items;

    public function __construct($uid, $items)
    {
        $this->uid = $uid;
        $this->items = $items;
    }

    public function placeOrder()
    {
        $orderId = parent::createOrder($this->uid);
        parent::insertOrderData($orderId, $this->items);
        $cartId = parent::selectCart($this->uid);
        parent::dropCartItems($cartId);
        parent::dropCart($cartId);
        parent::updateItemStockAmount($this->items);
        return ["success" => true, "message" => "Order has been created"];
    }
}