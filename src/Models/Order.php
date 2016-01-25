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
    const TYPE_IOC = "ioc";
    const TYPE_FOK = "fok";
    const TYPE_MARKET = "market";
    
    /**
     * @var bool
     */
    public $open = false;

    public function __construct()
    {
        # code...
    }
}
