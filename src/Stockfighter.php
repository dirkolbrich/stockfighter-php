<?php
namespace DirkOlbrich\Stockfighter;

use GuzzleHttp\Client as HttpClient;

/**
* 
*/
class Stockfighter
{
    protected $base_url = 'https://api.stockfighter.io/ob/api/';
    
    protected $config = array();

    function __construct($config = array())
    {
        $this->config = $config;
    }

    /**
     * 
     */
    public function heartbeat() {
        $client = new HttpClient(['base_uri' => $this->base_url]);
        $response = $client->get('heartbeat');
        $content = json_decode($response->getBody()->getContents());
        return $content->ok;
    }

    /**
     * 
     */
    public function venue($venue) {
        $client = new HttpClient(['base_uri' => $this->base_url]);
        $response = $client->get('venues/' . $venue . '/heartbeat');
        $content = json_decode($response->getBody()->getContents());
        return $content->ok;
    }
}

