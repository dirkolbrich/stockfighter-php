<?php
namespace DirkOlbrich\Stockfighter\Models;

/**
 * A single transaction.
 */
class Transaction
{
    /**
     * md5 hash based on venue, stock, orderID and fill-ts
     * @var string
     */
    public $transactionId = '';

    /**
     * @var string
     */
    public $orderId = '';

    /**
     * @var string
     */
    public $orderTs = '';

    /**
     * @var string
     */
    public $direction = 'buy';

    /**
     * @var string
     */
    public $venue = '';

    /**
     * @var string
     */
    public $stock = '';

    /**
     * @var int
     */
    public $price = 0;

    /**
     * @var int
     */
    public $qty = 0;

    /**
     * @var int
     */
    public $value = 0;

    /**
     * @var string
     */
    public $ts = '';

    /**
     * @param string $order
     * @param string $fill
     */
    public function __construct($order, $fill)
    {
        // set id from md5 hash
        $md5 = $order->venue . $order->stock . $order->orderId . $fill->ts;
        $this->transactionId = md5($md5);

        $this->orderId = $order->orderId;
        $this->orderTs = $order->ts;
        $this->direction = $order->direction;
        $this->venue = $order->venue;
        $this->stock = $order->stock;
        $this->price = $fill->price;
        $this->qty = $fill->qty;
        $this->ts = $fill->ts;

        $this->value();
    }

    protected function value()
    {
        $this->value = $this->price * $this->qty;
    }
}
