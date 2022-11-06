<?php

namespace App\Presentation\Http\Controller\Cart;

use App\Application\AddProductToCartAction;
use App\Application\GetCartAction;
use App\Domain\Exception\CartAlreadyBoughtException;
use App\Domain\Exception\InvalidAmountException;
use App\Domain\Exception\ProductNotFound;
use App\Presentation\Http\Controller\Controller;
use App\Presentation\Http\Exception\BadRequestException;
use App\Presentation\Http\Request\Cart\PostCartRequest;
use App\Presentation\Http\Response\Cart\PostCartResponse;
use App\Presentation\Http\Response\ErrorResponse;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class PostCartController extends Controller
{
    public function __construct(
        private readonly GetCartAction $getCartAction,
        private readonly AddProductToCartAction $addProductToCartAction,
    )
    {
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $cart = ($this->getCartAction)();

        try {
            $parsedRequest = new PostCartRequest($request);
        } catch (BadRequestException $exception) {
            return $this->buildResponseFromBadRequestException($exception, $response);
        }

        $errors = [];

        foreach ($parsedRequest->items as $key => $item) {
            try {
                ($this->addProductToCartAction)($cart, $item->id, $item->amount);
            } catch (CartAlreadyBoughtException $e) {
                $errors['items'] = $e->getMessage();
                break;
            } catch (InvalidAmountException | ProductNotFound $e) {
                $errors["items.{$key}"] = $e->getMessage();
            } catch (\Exception) {
                $errors["items.{$key}"] = "Unknown error";
            }
        }

        return (new PostCartResponse($cart, $errors))->build($response);
    }
}
