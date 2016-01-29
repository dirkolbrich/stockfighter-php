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
     * @return Order $order
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

    /**
     * get all open orders from $this->orders
     * @return array
     */
    public function open()
    {
        return array_filter(
            $this->orders,
            function ($order) {
                return ($order->isOpen());
            }
        );
    }

    /**
     * get all closed orders from $this->orders
     * @return array
     */
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
     * @return string $order
     */
    public function addOrder($response)
    {
        $order = new Order($response);
        $this->orders[] = $order;
        return $order;
    }

    /**
     * update an existing order with new info from API response
     * @param int $orderId
     * @param string $response
     * @return string $order
     */
    public function updateOrder($orderId, $response)
    {
        // iterate $this->orders and find $order with $orderId
        $orders = array_filter(
            $this->orders,
            function ($order) use ($orderId) {
                return ($order->orderId == $orderId);
            }
        );
        // sanity check, compare venue, stock and timestamp
        foreach ($orders as $key => $item) {
            if ($item->venue == $response->venue &&
                $item->stock == $response->symbol &&
                $item->ts == $response->ts) {
                // replace order in $this->orders with $response
                $order = new Order($response);
                $this->orders[$key] = $order;
            }
        }
        return $order;
    }
}
