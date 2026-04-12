<?php
require_once __DIR__ . '/env.php';
require_once __DIR__ . '/../vendor/autoload.php';

$mongoUri    = getenv('MONGODB_URI') ?: 'mongodb://localhost:27017';
$mongoDb     = getenv('MONGODB_DB')  ?: 'humanrights_db';
$mongoClient = new MongoDB\Client($mongoUri);
$db          = $mongoClient->selectDatabase($mongoDb);
