<?php

namespace App\Presentation\Http\Controller;

use App\Application\AddProductToCart\AddProductToCartAction;
use App\Application\GetCart\GetCartAction;
use App\Domain\Exception\CartAlreadyBoughtException;
use App\Domain\Exception\InvalidAmountException;
use App\Domain\Exception\ProductNotFound;
use App\Presentation\Http\Request\PostCartRequest;
use App\Presentation\Http\Response\PostCartResponse;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class PostCartController
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
        $parsedRequest = new PostCartRequest($request);

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
