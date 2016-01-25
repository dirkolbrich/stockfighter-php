<?php
namespace DirkOlbrich\Stockfighter\Models;

/**
 * A single account.
 */
class Account
{
    /**
     * @var int
     */
    public $accountId;

    /**
     * @var array
     */
    public $inventory = [];

    /**
     * The balance of this Account.
     * @var int
     */
    public $balance = 0;

    /**
     * @param int $id
     */
    public function __construct($id)
    {
        $this->accountId = $id;
    }
}
