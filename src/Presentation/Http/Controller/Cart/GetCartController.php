<?php

namespace App\Presentation\Http\Controller\Cart;

use App\Application\GetCartAction;
use App\Presentation\Http\Response\Cart\GetCartResponse;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GetCartController
{
    public function __construct(
        private readonly GetCartAction $getCartAction
    )
    {
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $cart = ($this->getCartAction)();

        return (new GetCartResponse($cart))->build($response);
    }
}
