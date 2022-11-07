<?php

use App\Domain\Enum\UserType;
use App\Domain\Repository\User\GetUserByIdRepository;
use App\Domain\Security\TokenHandler;
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
use App\Presentation\Http\Middleware\UserOfTypeAuthenticatedMiddleware;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface;

return function (App $app) {
    $app->group('/api', function (RouteCollectorProxyInterface $apiGroup) use ($app) {
        $apiGroup->get('/cart', GetCartController::class);
        $apiGroup->post('/cart', PostCartController::class);

        $apiGroup->get('/products', GetAllProductsController::class);
        $apiGroup->get('/products/{id}', GetProductController::class);

        $apiGroup->group('', function (RouteCollectorProxyInterface $protectedGroup) {
            $protectedGroup->patch('/cart', PatchCartController::class);
        })->addMiddleware(
            new UserOfTypeAuthenticatedMiddleware(
                $app->getContainer()->get(TokenHandler::class),
                $app->getContainer()->get(GetUserByIdRepository::class),
                [UserType::Customer, UserType::Manager]
            )
        );

        $apiGroup->group('', function (RouteCollectorProxyInterface $protectedGroup) {
            $protectedGroup->delete('/products/{id}', DeleteProductController::class);
            $protectedGroup->put('/products/{id}', PutProductController::class);
            $protectedGroup->post('/products', PostProductController::class);
        })->addMiddleware(
            new UserOfTypeAuthenticatedMiddleware(
                $app->getContainer()->get(TokenHandler::class),
                $app->getContainer()->get(GetUserByIdRepository::class),
                [UserType::Manager]
            )
        );

        $apiGroup->post('/user', PostUserController::class);
        $apiGroup->post('/login', LoginUserController::class);
    })->addMiddleware(new JsonBodyParserMiddleware());
};
