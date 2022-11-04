<?php

use App\Presentation\Http\Controller\Cart\GetCartController;
use App\Presentation\Http\Controller\Cart\PatchCartController;
use App\Presentation\Http\Controller\Cart\PostCartController;
use App\Presentation\Http\Middleware\JsonBodyParserMiddleware;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface;

return function (App $app) {
    $app->get('/hello/{name}', function ($request, $response, $name) {
        $response->getBody()->write("Hello " . $name);

        return $response;
    });

    $app->group('/api', function (RouteCollectorProxyInterface $group) {
        $group->get('/cart', GetCartController::class);
        $group->post('/cart', PostCartController::class);
        $group->patch('/cart', PatchCartController::class);
    })->addMiddleware(new JsonBodyParserMiddleware());
};
