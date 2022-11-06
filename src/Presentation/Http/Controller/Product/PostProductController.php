<?php

namespace App\Presentation\Http\Controller\Product;

use App\Application\CreateProductAction;
use App\Presentation\Http\Controller\Controller;
use App\Presentation\Http\Exception\BadRequestException;
use App\Presentation\Http\Request\Product\ProductRequest;
use App\Presentation\Http\Response\Product\ProductResponse;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class PostProductController extends Controller
{
    public function __construct(
        private readonly CreateProductAction $createProductAction
    )
    {
    }

    public function __invoke(Request $request, Response $response): Response
    {
        try {
            $parsedRequest = new ProductRequest($request);
        } catch (BadRequestException $exception) {
            return $this->buildResponseFromBadRequestException($exception, $response);
        }

        $product = ($this->createProductAction)($parsedRequest->name, $parsedRequest->description, $parsedRequest->price);

        return (new ProductResponse($product))->build($response);
    }
}
