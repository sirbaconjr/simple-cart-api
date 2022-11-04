<?php

namespace App\Presentation\Http\Controller;

use App\Application\CheckoutCart\CheckoutCartAction;
use App\Application\GetCart\GetCartAction;
use App\Domain\Enum\CartStatus;
use App\Presentation\Http\Request\PatchCartRequest;
use App\Presentation\Http\Response\PatchCartResponse;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class PatchCartController
{
    public function __construct(
        private readonly CheckoutCartAction $checkoutCartAction,
        private readonly GetCartAction $getCartAction
    )
    {
    }

    public function __invoke(Request $request, Response $response)
    {
        $parsedRequest = new PatchCartRequest($request);

        if ($parsedRequest->status != CartStatus::Bought) {
            throw new \Exception('PatchCart');
        }

        $cart = ($this->getCartAction)();

        ($this->checkoutCartAction)($cart);

        return (new PatchCartResponse($cart))($response);
    }
}
