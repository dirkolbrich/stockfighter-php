<?php
namespace DirkOlbrich\Stockfighter\Models;

class Symbol
{
    /**
     * The symbol of the symbol.
     * @var string
     */
    public $symbol;

    /**
     * The name of the symbol.
     * @var string
     */
    public $name;

    /**
     * @param string $symbol
     * @param string $name
     */
    public function __construct($symbol, $name)
    {
        $this->symbol = $symbol;
        $this->name = $name;
    }
}
