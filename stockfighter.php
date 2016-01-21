#!/usr/bin/env php
<?php

// Composer autoload.
require __DIR__ . '/vendor/autoload.php';

use DirkOlbrich\Stockfighter\Stockfighter;

$stockfighter = new Stockfighter;
var_dump($stockfighter->venue('TESTEX'));