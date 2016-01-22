#!/usr/bin/env php
<?php

// Composer autoload.
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use DirkOlbrich\Stockfighter\Stockfighter;

// set the API key via .env file
$dotenv = new Dotenv(__DIR__);
$dotenv->load();

// load the stockfighter app
$stockfighter = new Stockfighter;
var_dump($stockfighter->venue_heartbeat('TESTEX'));