<?php
namespace DirkOlbrich\Stockfighter;

use GuzzleHttp\Client as HttpClient;

/**
* The basic Stockfighter.io api
* returns the pure json response as string
*/
class StockfighterApi
{
    const BASE_URI = 'https://api.stockfighter.io';
    const BASE_API = '/ob/api/';
    const BASE_GM = '/gm/';
    const WEB_SOCKET = 'wss://api.stockfighter.io/ob/api/ws';
    
    protected $api_key = '';
    protected $client = '';

    public function __construct($apiKey)
    {
        $this->api_key = $apiKey;
        $this->client = new HttpClient([
            'base_uri' => self::BASE_URI,
            'headers' => ['X-Starfighter-Authorization' => $this->api_key]
        ]);
    }

    // Heartbeat calls

    /**
     * basic check if api is up
     * @return string
     */
    public function heartbeat()
    {
        $response = $this->client->get(self::BASE_API . 'heartbeat');
        return $response->getBody()->getContents();
    }

    /**
     * check if venue is up
     * @param string $venue
     * @return string
     */
    public function venueHeartbeat($venue)
    {
        $response = $this->client->get(self::BASE_API . 'venues/' . $venue . '/heartbeat');
        return $response->getBody()->getContents();
    }

    // Venue calls

    /**
     * list all available venues
     * @return string
     */
    public function venues()
    {
        $response = $this->client->get(self::BASE_API . 'venues');
        return $response->getBody()->getContents();
    }

    /**
     * list all available stocks at specific venue
     * @param string $venue
     * @return string
     */
    public function stocks($venue)
    {
        $response = $this->client->get(self::BASE_API . 'venues/' . $venue . '/stocks');
        return $response->getBody()->getContents();
    }

    /**
     * list all orders for specific account at specific venue
     * @param string $account
     * @param string $venue
     * @return string
     */
    public function orders($account, $venue)
    {
        $response = $this->client->get(
            self::BASE_API
            . 'venues/' . $venue . '/accounts/' . $account . '/orders'
        );
        return $response->getBody()->getContents();
    }

    /**
     * list all orders for specific account at specific venue
     * @param string $account
     * @param string $venue
     * @param string $stock
     * @return string
     */
    public function stockOrders($account, $venue, $stock)
    {
        $response = $this->client->get(
            self::BASE_API
            . 'venues/' . $venue . '/accounts/' . $account
            . '/stocks/' . $stock . '/orders'
        );
        return $response->getBody()->getContents();
    }

    // Stock calls

    /**
     * list the orderbook fpr specific stock at specific venue
     * @param string $venue
     * @param string $stock
     * @return string
     */
    public function orderbook($venue, $stock)
    {
        $response = $this->client->get(
            self::BASE_API . 'venues/' . $venue . '/stocks/' . $stock
        );
        return $response->getBody()->getContents();
    }

    /**
     * get the actual quote for specific stock at specific venue
     * @param string $venue
     * @param string $stock
     * @return string
     */
    public function quote($venue, $stock)
    {
        $response = $this->client->get(
            self::BASE_API . 'venues/' . $venue . '/stocks/' . $stock . '/quote'
        );
        return $response->getBody()->getContents();
    }

    // Order calls

    /**
     * place an order
     * @param string $venue
     * @param string $stock
     * @param array $order
     * @return string
     */
    public function order($venue, $stock, $order)
    {
        // var_dump($venue);
        // var_dump($stock);
        // var_dump($order);
        $response = $this->client->post(
            self::BASE_API . 'venues/' . $venue . '/stocks/' . $stock . '/orders',
            [ "json" => $order ]
        );
        return $response->getBody()->getContents();
    }

    /**
     * cancel an order
     * @param string $venue
     * @param string $stock
     * @param int $orderId
     * @return string
     */
    public function cancel($venue, $stock, $orderId)
    {
        $response = $this->client->delete(
            self::BASE_API . 'venues/' . $venue . '/stocks/' . $stock . '/orders/'. $orderId
        );
        return $response->getBody()->getContents();
    }

    /**
     * status for an order
     * @param string $venue
     * @param string $stock
     * @param int $orderId
     * @return string
     */
    public function orderStatus($venue, $stock, $orderId)
    {
        $response = $this->client->get(
            self::BASE_API . 'venues/' . $venue . '/stocks/' . $stock . '/orders/'. $orderId
        );
        return $response->getBody()->getContents();
    }

    // GameMaster calls

    // not implemented yet
    public function levels()
    {
        $response = $this->client->get(self::BASE_API . 'ui/levels/');
        return $response->getBody()->getContents();
    }

    /**
     * start a new level
     * @param string $level
     */
    public function start($level)
    {
        $response = $this->client->post(self::BASE_GM . 'levels/' . $level);
        return $response->getBody()->getContents();
    }

    /**
     * restart a level
     * @param int $instanceId
     */
    public function restart($instanceId)
    {
        $response = $this->client->post(self::BASE_GM . 'instances/' . $instanceId . '/restart');
        return $response->getBody()->getContents();
    }

    /**
     * stop a level
     * @param int $instanceId
     */
    public function stop($instanceId)
    {
        $response = $this->client->post(self::BASE_GM . 'instances/' . $instanceId . '/stop');
        return $response->getBody()->getContents();
    }

    /**
     * resume a level
     * @param int $instanceId
     */
    public function resume($instanceId)
    {
        $response = $this->client->post(self::BASE_GM . 'instances/' . $instanceId . '/resume');
        return $response->getBody()->getContents();
    }

    /**
     * status of a level
     * @param int $instanceId
     */
    public function levelStatus($instanceId)
    {
        $response = $this->client->get(self::BASE_GM . 'instances/' . $instanceId);
        return $response->getBody()->getContents();
    }

    // Web Socket calls
}
