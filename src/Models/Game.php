<?php
namespace DirkOlbrich\Stockfighter\Models;

/**
* single Game instance
*/
class Game
{
    /**
     * @var string
     */
    public $instanceId;

    /**
     * The trading accounts for this game.
     * @var string
     */
    public $account;

    /**
     * @var array
     */
    public $instructions;

    /**
     * @var array
     */
    public $tickers;

    /**
     * @var array
     */
    public $venues;

    /**
     * @var int
     */
    public $secondsPerTradingDay = 0;

    /**
     * @param array $config
     */
    public function __construct($config)
    {
        $this->instanceId = $config->instanceId;
        $this->account = $config->account;
        $this->instructions = $config->instructions;
        $this->tickers = $config->tickers;
        $this->venues = $config->venues;
        $this->secondsPerTradingDay = $config->secondsPerTradingDay;
    }
}
