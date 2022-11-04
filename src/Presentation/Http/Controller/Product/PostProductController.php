<?php

namespace App\Presentation\Http\Controller\Product;

use App\Application\CreateProductAction;
use App\Presentation\Http\Request\Product\ProductRequest;
use App\Presentation\Http\Response\Product\ProductResponse;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class PostProductController
{
    public function __construct(
        private readonly CreateProductAction $createProductAction
    )
    {
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $parsedRequest = new ProductRequest($request);

        $product = ($this->createProductAction)($parsedRequest->name, $parsedRequest->description, $parsedRequest->price);

        return (new ProductResponse($product))->build($response);
    }
}
