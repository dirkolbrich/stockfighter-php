<?php
namespace DirkOlbrich\Stockfighter\Models;

use DirkOlbrich\Stockfighter\Models\Order;

/**
* The Orderbook.
*/
class Orderbook
{
    /**
     * @var array
     */
    public $orders = [];

    public function __construct()
    {
        //
    }

    /**
     * @param int $orderId
     */
    public function order($orderId)
    {
        return current(array_filter(
            $this->orders,
            function ($order) use ($orderId) {
                return ($order->orderId == $orderId);
            }
        ));
    }

    public function open()
    {
        return array_filter(
            $this->orders,
            function ($order) {
                return ($order->isOpen());
            }
        );
    }

    public function closed()
    {
        return array_filter(
            $this->orders,
            function ($order) {
                return (!$order->isOpen());
            }
        );
    }

    /**
     * @param string $response
     */
    public function add($response)
    {
        $order = new Order($response);
        $this->orders[] = $order;
    }

    /**
     * @param int $orderId
     * @param strin $response
     */
    public function close($orderId, $response)
    {
        // iterate $this->orders and find $order with $orderId
        $order = $this->order($orderId);
        // compare $order with response
        var_dump($response);
        $newOrder = new Order($response);
        var_dump($order == $newOrder);
        // set $order->open to false
        $order->close();
    }

    /**
     * @param int $orderId
     * @param string $response
     */
    public function update($orderId, $response)
    {
        // iterate $this->orders and find $order with $orderId
        // validate if order is open
        // compare order with new order
        // simple replace order?
        // check if new order is open
    }
}
