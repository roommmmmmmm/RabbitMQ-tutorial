<?php
require __DIR__ . '/vendor/autoload.php';
require './Httpserver.php';

$server = new \HttpServer('0.0.0.0', 2333);
$server->run();
