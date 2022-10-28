<?php

require __DIR__ . '/../vendor/autoload.php';

$dotenv = new \Symfony\Component\Dotenv\Dotenv();
$dotenv->load(__DIR__ . '/../.env');

$containerBuilder = new \DI\ContainerBuilder();

// Import services
$dependencies = require __DIR__ . '/../app/services.php';
$dependencies($containerBuilder);

// Initialize app with PHP-DI
return $containerBuilder->build();
