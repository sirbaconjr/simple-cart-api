<?php

use Slim\Factory\AppFactory;
use Slim\Handlers\Strategies\RequestResponseArgs;

$container = require __DIR__ . '/container.php';

AppFactory::setContainer($container);

$app = AppFactory::create();

$routeCollector = $app->getRouteCollector();
$routeCollector->setDefaultInvocationStrategy(new RequestResponseArgs());

// Register routes
$routes = require __DIR__ . '/routes.php';
$routes($app);

$displayErrorDetails = $container->get('environment') != 'prod';
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, true, true);

// Error Handler
$errorHandler = $errorMiddleware->getDefaultErrorHandler();
$errorHandler->forceContentType('application/json');

return $app;
