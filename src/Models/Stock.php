<?php
namespace DirkOlbrich\Stockfighter\Models;

use DirkOlbrich\Stockfighter\Models\Symbol;

/**
 * A single Stock
 */
class Stock
{
    /**
     * ticker symbol of the stock
     * @var Symbol
     **/
    public $symbol;

    public function __construct($config)
    {
        $this->symbol = new Symbol($config->symbol, $config->name);
    }
}
