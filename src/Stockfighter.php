<?php
namespace DirkOlbrich\Stockfighter;

use DirkOlbrich\Stockfighter\Models\Game;
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
     * @var string
     */
    protected $stock = '';

    /**
     * @var array
     */
    public $stocks = [];

    /**
     * @var string
     */
    protected $venue = '';

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

    /**
     * @param string $venue
     * @return self
     */
    public function venue($venue = '')
    {
        if ($venue) {
            $this->venue = $venue;
        } elseif (empty($this->venue)) {
            $this->venue = $this->venues[0]->symbol->symbol;
        };
        return $this;
    }

    // Stock calls

    /**
     * @param string $stock
     * @return self
     */
    public function stock($stock = '')
    {
        if ($stock) {
            $this->stock = $stock;
        } elseif (empty($this->stock)) {
            $this->stock = $this->stocks[0]->symbol->symbol;
        }
        return $this;
    }

    /**
     * get the actual quote for specific stock at specific venue
     * @return string
     */
    public function quote()
    {
        $response = json_decode($this->api->quote(
            $this->venue()->venue,
            $this->stock()->stock
        ));
        return $response;
    }

    // Order calls

    /**
     * place a buy order
     * @param int $price
     * @param int $qty
     * @param string $type
     * @return string $order
     */
    public function buy($price, $qty, $type)
    {
        $order = [
            "account" => $this->account->accountId,
            "venue" => $this->venue()->venue,
            "stock" => $this->stock()->stock,
            "price" => $price,
            "qty" => $qty,
            "direction" => "buy",
            "orderType" => $type
        ];
        $response = json_decode($this->api->order(
            $this->venue()->venue,
            $this->stock()->stock,
            $order
        ));
        // add order to orderbook
        if ($response->ok) {
            $order = $this->orderbook->addOrder($response);
            $this->transaction($order);
        }
        return $order;
    }

    /**
     * place a sell order
     * @param int $price
     * @param int $qty
     * @param string $type
     * @return int $order
     */
    public function sell($price, $qty, $type)
    {
        $order = [
            "account" => $this->game->account,
            "venue" => $this->venue()->venue,
            "stock" => $this->stock()->stock,
            "price" => $price,
            "qty" => $qty,
            "direction" => "sell",
            "orderType" => $type
        ];
        $response = json_decode($this->api->order(
            $this->venue()->venue,
            $this->stock()->stock,
            $order
        ));
        // add order to orderbook
        if ($response->ok) {
            $order = $this->orderbook->addOrder($response);
            $this->transaction($order);
        }
        return $order;
    }

    /**
     * cancel an order
     * @param int $orderId
     * @return int $orderId
     */
    public function cancelOrder($orderId)
    {
        // get order from orderbook
        $order = $this->orderbook->order($orderId);
        $response = json_decode($this->api->cancel(
            $order->venue,
            $order->stock,
            $orderId
        ));
        // update order in the orderbook
        if ($response->ok) {
            $order = $this->orderbook->updateOrder($orderId, $response);
            $this->transaction($order);
        }
        return $order;
    }

    /**
     * cancel all open orders
     */
    public function cancelAll()
    {
        // get all open orders from the orderbook
        $orders = $this->orderbook->open;
        foreach ($orders as $order) {
            $this->cancelOrder($order->orderId);
        }
    }

    /**
     * update status of an order
     * @param int $orderId
     * @return string $order
     */
    public function updateOrder($orderId)
    {
        // get order from orderbook
        $order = $this->orderbook->order($orderId);
        // get status of order
        $response = json_decode($this->api->orderStatus(
            $order->venue,
            $order->stock,
            $order->orderId
        ));
        // update order in orderbook
        if ($response->ok) {
            $order = $this->orderbook->updateOrder($orderId, $response);
            $this->transaction($order);
        }
        return $order;
    }

    // Orderbook calls

    /**
     * get orderbook for a specific stock at a specific market
     * @return string
     */
    public function orderbook()
    {
        $response = json_decode($this->api->orderbook(
            $this->venue()->venue,
            $this->stock()->stock
        ));
        return $response;
    }

    /**
     * update all open orders in the orderbook
     */
    public function orderbookUpdate()
    {
        // get all open orders from orderbook
        $orders = $this->orderbook->open();
        foreach ($orders as $order) {
            // update order in orderbook
            $this->updateOrder($order->orderId);
        }
    }

    // Account calls

    public function accountUpdate()
    {
        
    }

    /**
     * add or update a transaction for this order to the account
     * @param string $order
     */
    public function transaction($order)
    {
        // get order and fills from order
        $fills = $order->fills();
        if ($fills) {
            // add transaction to account
            $this->account->addTransaction($order, $fills);
        }
    }

    // GameMaster calls

    /**
     * @return string
     */
    public function levels()
    {
        $response = $this->api->levels();
        return $response;
    }

    /**
     * start a new game
     * @param string $level
     */
    public function gameStart($level)
    {
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
                        $this->stocks[] = new Stock($symbol);
                    }
                }
            }
        }
    }

    public function gameStatus()
    {
        $response = $this->api->levelStatus($this->game->instanceId);
        return $response;
    }

    public function gameStop()
    {
        $response = $this->api->stop($this->game->instanceId);
        return $response;
    }

    public function gameRestart()
    {
        $response = $this->api->restart($this->game->instanceId);
        return $response;
    }
}
