<?php
namespace DirkOlbrich\Stockfighter;

use DirkOlbrich\Stockfighter\Models\Game;
use DirkOlbrich\Stockfighter\Models\Order;
use DirkOlbrich\Stockfighter\Models\Stock;
use DirkOlbrich\Stockfighter\Models\Venue;
use DirkOlbrich\Stockfighter\Models\Account;
use DirkOlbrich\Stockfighter\Models\Orderbook;
use DirkOlbrich\Stockfighter\StockfighterApi;

/**
*
*/
class Stockfighter
{
    protected $api;
    public $game;
    public $account;
    public $orderbook;

    /**
     * @var array
     */
    public $stocks = [];

    /**
     * @var array
     */
    public $venues = [];
    
    public function __construct()
    {
        $this->api = new StockfighterApi();
    }

    // Heartbeat calls

    /**
     * basic check if api is up
     * @return bool
     */
    public function heartbeat()
    {
        $response = json_decode($this->api->heartbeat());
        return $response->ok;
    }

    /**
     * check if venue is up
     * @param string $venue
     * @return bool
     */
    public function venueHeartbeat($venue)
    {
        $response = json_decode($this->api->venueHeartbeat($venue));
        return $response->ok;
    }

    // Venue calls

    // Stock calls

    /**
     * get the actual quote for specific stock at specific venue
     * @param string $venue
     * @param string $stock
     * @return 
     */
    public function quote($venue, $stock)
    {
        $response = json_decode($this->api->quote($venue, $stock));
        return $response;
    }

    // Order calls

    /**
     * place a buy order
     * @param string $venue
     * @param string $stock
     * @param int $price
     * @param int $qty
     * @param string $type
     */
    public function buy($venue, $stock, $price, $qty, $type)
    {
        $order = new Order();
        $order = [
            "account" => $this->account->accountId,
            "venue" => $venue,
            "stock" => $stock,
            "price" => $price,
            "qty" => $qty,
            "direction" => "buy",
            "orderType" => $type
        ];
        $response = json_decode($this->api->order($venue, $stock, $order));
        return $response;
    }

    /**
     * place a sell order
     * @param string $venue
     * @param string $stock
     * @param int $price
     * @param int $qty
     * @param string $type
     */
    public function sell($venue, $stock, $price, $qty, $type)
    {
         $order = [
            "account" => $this->game->account,
            "venue" => $venue,
            "stock" => $stock,
            "price" => $price,
            "qty" => $qty,
            "direction" => "sell",
            "orderType" => $type
        ];
        $response = json_decode($this->api->order($venue, $stock, $order));
        return $response;       
    }

    public function cancel()
    {
        
    }
    
    // Orderbook calls

    // GameMaster calls

    public function levels()
    {
        $response = $this->api->levels();
        return $response;
    }

    /**
     * start a new game
     * @param string $level
     */
    public function startGame($level)
    {
        echo "in startGame()\n";
        $this->game = new Game(json_decode($this->api->start($level)));
        $this->account = new Account($this->game->account);
        $this->orderbook = new Orderbook();

        // set up venues list
        $venues = json_decode($this->api->venues());
        foreach ($this->game->venues as $venue) {
            foreach ($venues->venues as $item) {
                if ($item->venue == $venue) {
                    $this->venues[] = new Venue($item);
                }
            }
        }

        // set up stocks list
        foreach ($this->venues as $venue) {
            $stocks = json_decode($this->api->stocks($venue->symbol->symbol));
            foreach ($this->game->tickers as $ticker) {
                foreach ($stocks->symbols as $symbol) {
                    if ($symbol->symbol == $ticker) {
                        $this->stocks[] = new stock($symbol);
                    }
                }
            }
        }
    }


    public function statusGame()
    {
        $response = $this->api->statusLevel($this->game->instanceId);
        return $response;
    }

    public function stopGame()
    {
        $response = $this->api->stop($this->game->instanceId);
        return $response;
    }

    public function restartGame()
    {
        $response = $this->api->restart($this->game->instanceId);
        return $response;
    }
}
