<?php
namespace DirkOlbrich\Stockfighter\Models;

/**
 * A single Inventory position.
 */
class Inventory
{
    /**
     * @var string
     */
    public $stock = '';

    /**
     * @var int
     */
    public $qty = 0;

    /**
     * @var int
     */
    public $avgPrice = 0;

    /**
     * @param string $transaction
     */
    public function __construct($transaction)
    {
        $this->stock = $transaction->stock;
        $this->avgPrice = $transaction->price;

        switch ($transaction->direction) {
            case 'buy':
                $this->qty = $transaction->qty;
                break;            
            case 'sell':
                $this->qty = -$transaction->qty;
                break;
        }
    }
}
