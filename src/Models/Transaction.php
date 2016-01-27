<?php
namespace DirkOlbrich\Stockfighter\Models;

/**
 * A single transaction.
 */
class Transaction
{
    /**
     * @var int
     */
    public $transactionId;

    /**
     * @var string
     */
    public $direction = 'in';

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

    public function __construct()
    {
        //
    }
}
