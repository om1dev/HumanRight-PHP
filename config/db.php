<?php
require_once __DIR__ . '/../vendor/autoload.php';

$mongoUri    = 'mongodb+srv://tejasmehar7_db_user:Admin1234@cluster0.9fl0gp4.mongodb.net/?appName=Cluster0';
$mongoClient = new MongoDB\Client($mongoUri);
$db          = $mongoClient->selectDatabase('humanrights_db');
