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
    
    protected $api_key = '';

    function __construct()
    {
        $this->api_key = $_ENV('API_KEY');
    }

    // Heartbeat calls

    /**
     * basic check if api is up
     * @return string
     */
    public function heartbeat() {
        var_dump($_ENV);
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
