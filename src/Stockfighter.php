<?php
namespace DirkOlbrich\Stockfighter;

use DirkOlbrich\Stockfighter\StockfighterApi;

/**
* 
*/
class Stockfighter
{
    protected $api = null;
    
    protected $config = array();

    function __construct($config = array())
    {
        $this->config = $config;
        $this->api = new StockfighterApi();
    }

    // Heartbeat calls

    /**
     * basic check if api is up
     * @return bool
     */
    public function heartbeat() {
        $response = json_decode($this->api->heartbeat());
        return $response->ok;
    }

    /**
     * check if venue is up
     * @param string $venue
     * @return bool
     */
    public function venue_heartbeat($venue) {
        $response = json_decode($this->api->venue_heartbeat($venue));
        return $response->ok;
    }

    // Venue calls

    // Stock calls

    // GameMaster calls
}

