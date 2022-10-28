<?php

declare(strict_types=1);

use Slim\Factory\AppFactory;
use Slim\Handlers\Strategies\RequestResponseArgs;

$container = require __DIR__ . '/../app/container.php';

$ENV = $_ENV['ENV'] ?? 'dev';

AppFactory::setContainer($container);

$app = AppFactory::create();

$routeCollector = $app->getRouteCollector();
$routeCollector->setDefaultInvocationStrategy(new RequestResponseArgs());

// Register routes
$routes = require __DIR__ . '/../app/routes.php';
$routes($app);

$displayErrorDetails = $ENV == 'dev';
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, true, true);

// Error Handler
$errorHandler = $errorMiddleware->getDefaultErrorHandler();
$errorHandler->forceContentType('application/json');

$app->run();
