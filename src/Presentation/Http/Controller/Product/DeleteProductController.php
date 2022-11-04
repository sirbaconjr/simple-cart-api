<?php

namespace App\Presentation\Http\Controller\Product;

use App\Application\DeleteProductAction;
use App\Application\GetProductAction;
use App\Presentation\Http\Response\BooleanResponse;
use App\Presentation\Http\Response\Product\ProductResponse;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Symfony\Component\Uid\UuidV4;

class DeleteProductController
{
    public function __construct(
        private readonly DeleteProductAction $deleteProductAction
    )
    {
    }

    public function __invoke(Request $request, Response $response, string $id): Response
    {
        ($this->deleteProductAction)(UuidV4::fromString($id));

        return (new BooleanResponse(true))->build($response);
    }
}
