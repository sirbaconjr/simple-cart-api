<?php

require __DIR__ . '/../vendor/autoload.php';

$dotenv = new \Symfony\Component\Dotenv\Dotenv();
$dotenv->load(__DIR__ . '/../.env');

$containerBuilder = new \DI\ContainerBuilder();

$ENV = $_ENV['ENV'] ?? 'dev';

// Import services
$containerBuilder->addDefinitions([
    'environment' => $ENV
]);
$containerBuilder->addDefinitions(__DIR__ . '/../app/services.php');

$envServices = __DIR__ . "/../app/services.$ENV.php";
if (file_exists($envServices)) {
    $containerBuilder->addDefinitions($envServices);
}

// Initialize app with PHP-DI
return $containerBuilder->build();
