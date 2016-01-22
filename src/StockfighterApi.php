<?php
namespace DirkOlbrich\Stockfighter;

use GuzzleHttp\Client as HttpClient;

/**
* The basic Stockfighter.io api
* returns the pure json response as string
*/
class StockfighterApi
{
    protected $base_uri = 'https://api.stockfighter.io';
    protected $base_api = '/ob/api/';
    protected $base_gm = '/gm';
    protected $web_socket = 'wss://api.stockfighter.io/ob/api/ws';
    
    protected $config = array();

    function __construct($config = array())
    {
        $this->config = $config;
    }

    // Heartbeat calls

    /**
     * basic check if api is up
     * @return string
     */
    public function heartbeat() {
        $client = new HttpClient(['base_uri' => $this->base_uri . $this->base_api]);
        $response = $client->get('heartbeat');
        return $response->getBody()->getContents();
    }

    /**
     * check if venue is up
     * @param string $venue
     * @return string
     */
    public function venue_heartbeat($venue) {
        $client = new HttpClient(['base_uri' => $this->base_uri . $this->base_api]);
        $response = $client->get('venues/' . $venue . '/heartbeat');
        return $response->getBody()->getContents();
    }

    // Venue calls

    // Stock calls

    // GameMaster calls

    // Web Socket calls
}
