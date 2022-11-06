<?php

namespace App\Presentation\Http\Controller\Cart;

use App\Application\CheckoutCartAction;
use App\Application\GetCartAction;
use App\Domain\Enum\CartStatus;
use App\Presentation\Http\Controller\Controller;
use App\Presentation\Http\Exception\BadRequestException;
use App\Presentation\Http\Request\Cart\PatchCartRequest;
use App\Presentation\Http\Response\Cart\PatchCartResponse;
use App\Presentation\Http\Response\ErrorResponse;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class PatchCartController extends Controller
{
    public function __construct(
        private readonly CheckoutCartAction $checkoutCartAction,
        private readonly GetCartAction $getCartAction
    )
    {
    }

    public function __invoke(Request $request, Response $response)
    {
        try {
            $parsedRequest = new PatchCartRequest($request);
        } catch (BadRequestException $exception) {
            return $this->buildResponseFromBadRequestException($exception, $response);
        }

        $cart = ($this->getCartAction)();

        ($this->checkoutCartAction)($cart);

        return (new PatchCartResponse($cart))->build($response);
    }
}
