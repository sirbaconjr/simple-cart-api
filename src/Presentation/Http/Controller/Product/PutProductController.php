<?php

namespace App\Presentation\Http\Controller\Product;

use App\Application\UpdateProductAction;
use App\Presentation\Http\Request\Product\ProductRequest;
use App\Presentation\Http\Response\BooleanResponse;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Symfony\Component\Uid\UuidV4;

class PutProductController
{
    public function __construct(
        private readonly UpdateProductAction $updateProductAction
    )
    {
    }

    public function __invoke(Request $request, Response $response, string $id): Response
    {
        $parsedId = UuidV4::fromString($id);

        $parsedRequest = new ProductRequest($request);

        ($this->updateProductAction)($parsedId, $parsedRequest->name, $parsedRequest->description, $parsedRequest->price);

        return (new BooleanResponse(true))->build($response);
    }
}
