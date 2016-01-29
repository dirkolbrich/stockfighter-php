<?php
namespace DirkOlbrich\Stockfighter\Models;

use DirkOlbrich\Stockfighter\Models\Inventory;
use DirkOlbrich\Stockfighter\Models\Transaction;

/**
 * A single account.
 */
class Account
{
    /**
     * @var string
     */
    public $accountId;

    /**
     * @var array
     */
    public $inventory = [];

    /**
     * @var array
     */
    public $transactions = [];

    /**
     * The balance of this Account.
     * @var int
     */
    public $balance = 0;

    /**
     * @param string $id
     */
    public function __construct($id)
    {
        $this->accountId = $id;
    }

    /**
     * @param string $venue
     */
    public function transactionsForVenue($venue)
    {
        return array_filter($this->transactions,
            function ($transaction) use ($venue) {
                return $transaction->venue == $venue;
        });
    }

    /**
     * @param string $stock
     */
    public function transactionsForStock($stock)
    {
        return array_filter($this->transactions,
            function ($transaction) use ($stock) {
                return $transaction->stock == $stock;
        });
    }

    /**
     * @param int $orderId
     */
    public function transactionsForOrder($orderId)
    {
        return array_filter($this->transactions,
            function ($transaction) use ($orderId) {
                return $transaction->orderId == $orderId;
        });
    }

    /**
     * @param Order $order
     * @param array $fills
     */
    public function addTransaction($order, $fills)
    {
        // get all existing transactions for this stock
        $transactions = $this->transactionsForStock($order->stock);
        foreach ($fills as $key => $fill) {
            // create new transaction
            $transaction = new Transaction($order, $fill);
            // check if transaction already exists for this fill
            if (false !== array_search($transaction->transactionId, array_column($transactions, 'transactionId'))) {
                // existing duplicate in transaction found
                continue;
            }
            // add transaction to transaction list
            $this->transactions[] = $transaction;
            // update the inventory for this stock
            $this->updateInventory($transaction);
            // update the balance bases on the transaction
            $this->updateBalance($transaction);
        }
    }

    /**
     * @param string $transaction
     */
    public function updateInventory($transaction)
    {
        // check inventory for existing postion with this stock
        $pos = current(array_filter($this->inventory,
            function ($pos) use ($transaction) {
                return $pos->stock == $transaction->stock;
            }));
        // update position
        if ($pos) {
            switch ($transaction->direction) {
                case 'buy':
                    $pos->avgPrice = round(
                        ($pos->avgPrice * abs($pos->qty) + $transaction->value) / 
                        (abs($pos->qty) + abs($transaction->qty))
                    );
                    $pos->qty += $transaction->qty;
                    break;  
                case 'sell':
                    $pos->qty -= $transaction->qty;
                    // average price of the position doesn't change
                    break;
            }
        // or add new position to inventory
        } else {
            $pos = new Inventory($transaction);
            $this->inventory[] = $pos;
        }
    }

    /**
     * @param string $transaction
     */
    public function updateBalance($transaction)
    {
        // var_dump($transaction->direction);
        switch ($transaction->direction) {
            case 'buy':
                $this->balance -= $transaction->value;
                break;        
            case 'sell':
                $this->balance += $transaction->value;
                break;
        }
    }
}
