<?php

namespace App\Presentation\Http\Controller\Cart;

use App\Application\CheckoutCartAction;
use App\Application\GetCartAction;
use App\Domain\Exception\CartAlreadyBoughtException;
use App\Domain\Exception\EmptyCartException;
use App\Presentation\Http\Controller\Controller;
use App\Presentation\Http\Exception\BadRequestException;
use App\Presentation\Http\Request\Cart\PatchCartRequest;
use App\Presentation\Http\Response\Cart\PatchCartResponse;
use Psr\Http\Message\ResponseInterface;
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

    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        try {
            $parsedRequest = new PatchCartRequest($request);

            $cart = ($this->getCartAction)();

            ($this->checkoutCartAction)($cart);
        } catch (BadRequestException $exception) {
            return $this->buildResponseFromBadRequestException($exception, $response);
        } catch (CartAlreadyBoughtException|EmptyCartException $exception) {
            return $this->buildResponseFromAnyException('id', $exception, $response);
        }

        return (new PatchCartResponse($cart))->build($response);
    }
}
