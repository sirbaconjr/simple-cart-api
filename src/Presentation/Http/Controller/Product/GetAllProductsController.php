<?php

namespace App\Presentation\Http\Controller\Product;

use App\Application\GetAllProductsAction;
use App\Presentation\Http\Response\Product\GetAllProductsResponse;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class GetAllProductsController
{
    public function __construct(
        private readonly GetAllProductsAction $getAllProductsAction
    )
    {
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $products = ($this->getAllProductsAction)();

        return (new GetAllProductsResponse($products))->build($response);
    }
}
