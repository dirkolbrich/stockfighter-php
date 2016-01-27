<?php
namespace DirkOlbrich\Stockfighter\Models;

/**
* A single Order.
*/
class Order
{
    // Order Direction:
    const DIR_BUY = "buy";
    const DIR_SELL = "sell";
    
    // Order Types:
    const TYPE_LIMIT = "limit";
    const TYPE_IOC = "immediate-or-cancel";
    const TYPE_FOK = "fill-or-kill";
    const TYPE_MARKET = "market";
    
    public $account = '';
    public $venue = '';
    public $stock = '';

    public $orderId;
    public $direction;
    public $orderType;
    public $price;
    public $originalQty;
    public $qty;
    public $ts;
    public $fills = [];
    public $totalFilled = 0;

    /**
     * @var bool
     */
    public $open = false;

    /**
     * @param string $order
     */
    public function __construct($order)
    {
        $this->account = $order->account;
        $this->venue = $order->venue;
        $this->stock = $order->symbol;
        $this->orderId = $order->id;
        $this->direction = $order->direction;
        $this->orderType = $order->orderType;
        $this->price = $order->price;
        $this->originalQty = $order->originalQty;
        $this->qty = $order->qty;
        $this->ts = $order->ts;
        $this->fills = $order->fills;
        $this->totalFilled = $order->totalFilled;
        $this->open = $order->open;
    }

    /**
     * @return bool
     */
    public function isOpen()
    {
        return $this->open;
    }

    /**
     * set $open to false
     */
    public function close()
    {
        $this->open = false;
    }
}
