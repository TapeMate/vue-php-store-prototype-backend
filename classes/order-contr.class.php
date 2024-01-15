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
        parent::createOrder($this->uid);
        return ["success" => true, "message" => "Order has been created"];
        // parent::setOrderItems($orderId, $this->items);
    }

}