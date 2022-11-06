<?php

use App\Presentation\Http\Controller\Cart\GetCartController;
use App\Presentation\Http\Controller\Cart\PatchCartController;
use App\Presentation\Http\Controller\Cart\PostCartController;
use App\Presentation\Http\Controller\Product\DeleteProductController;
use App\Presentation\Http\Controller\Product\GetAllProductsController;
use App\Presentation\Http\Controller\Product\GetProductController;
use App\Presentation\Http\Controller\Product\PostProductController;
use App\Presentation\Http\Controller\Product\PutProductController;
use App\Presentation\Http\Controller\User\LoginUserController;
use App\Presentation\Http\Controller\User\PostUserController;
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

        $group->get('/products', GetAllProductsController::class);
        $group->get('/products/{id}', GetProductController::class);
        $group->delete('/products/{id}', DeleteProductController::class);
        $group->put('/products/{id}', PutProductController::class);
        $group->post('/products', PostProductController::class);

        $group->post('/user', PostUserController::class);
        $group->post('/login', LoginUserController::class);
    })->addMiddleware(new JsonBodyParserMiddleware());
};
