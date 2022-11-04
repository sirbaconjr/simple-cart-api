<?php

namespace App\Presentation\Http\Controller;

use App\Application\GetCart\GetCartAction;
use App\Presentation\Http\Response\GetCartResponse;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class GetCartController
{
    public function __construct(
        private readonly GetCartAction $getCartAction
    )
    {
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $cart = ($this->getCartAction)();

        return (new GetCartResponse($cart))->build($response);
    }
}
