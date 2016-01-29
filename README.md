# Stockfighter PHP API wrapper

My take on an API wrapper for stockfighter.io

It utilizes two base classes:

`StockfighterApi.php`, which is the minimal wrapper around the REST API of stockfighter.io. It returns the raw json response. See the [stockfighter API documentation](https://starfighter.readme.io/v1.0/docs). You can use this base class for you own implemantation.

`Stockfighter.php` is the actual game wrapper around the API. It depends on `StockfighterApi.php`. The system keeps track of your orders and your account with a list of the transactions, the inventory and your balance.

### Installation

Install via composer:
```json
"require": {
        "dirkolbrich/stockfighter": "*"
}
```

### Usage

#### API Class

To only use the base API class. Provide your API key during instantiation:
```php
use DirkOlbrich\Stockfighter\StockfighterApi;

$apiKey = "your API key provided by stockfighter.io";
$api = new StockfighterApi($apiKey);
```
The followings functions are available:
- `heartbeat()` - check if the REST API is up
- `venueHeartbeat($venue)` - check if the venue is up
- `venues()` - get all available venues
- `stocks($venue)` - get all available stocks on a specific venue
- `orders($account, $venue)` - get all orders on a specific venue
- `stockOrders($account, $venue, $stock)` - get all orders for a specific stock on a specific venue
- `orderbook($venue, $stock)` - get the orderbook of a specific stock on a specific venue
- `quote($venue, $stock)` - get the last quote of a specific stock on a specific venue
- `order($venue, $stock, $order)` - place an order, the `$order`param must be a string specified by the REST API
- `cancel($venue, $stock, $orderId)` - cancel an order
- `orderStatus($venue, $stock, $orderId)` - get the status of an order

Requests to the Game Master are available as well (self-explanatory):
- `levels()` - not implemented by the REST API yet
- `start($level)`
- `restart($instanceId)`
- `stop($instanceId)`
- `resume($instanceId)`
- `levelStatus($instanceId)` - get the status of the level

#### Stockfighter System

Or use the `Stockfighter` system:
```php
use DirkOlbrich\Stockfighter\Stockfighter;

$apiKey = "your API key provided by stockfighter.io";
$sf = new Stockfighter($apiKey);
```


### Dependencies

So far, it uses these dependencies.
```json
"require": {
        "guzzlehttp/guzzle": "~6.0",
}
```

### ToDo

- integrate web socket client
- tests, tests, tests
