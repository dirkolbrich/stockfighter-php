<?php
namespace DirkOlbrich\Stockfighter\Models;

use DirkOlbrich\Stockfighter\Models\Symbol;

/**
 * A single venue.
 */
class Venue
{
    /**
     * ticker symbol of the venue
     * @var Symbol
     **/
    public $symbol;

    /**
     * @var int
     */
    public $id = 0;

    /**
     * @var string
     */
    public $state;

    /**
     * @param mixed
     */
    public function __construct($config)
    {
        $this->symbol = new Symbol($config->venue, $config->name);
        $this->id = $config->id;
        $this->state = $config->state;
    }
}
