<?php

namespace App\Presentation\Http\Controller\Product;

use App\Application\GetProductAction;
use App\Domain\Exception\ProductNotFound;
use App\Presentation\Http\Controller\Controller;
use App\Presentation\Http\Exception\BadRequestException;
use App\Presentation\Http\Response\Product\ProductResponse;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Symfony\Component\Uid\UuidV4;

class GetProductController extends Controller
{
    public function __construct(
        private readonly GetProductAction $getProductAction
    )
    {
    }

    public function __invoke(Request $request, Response $response, string $id): Response
    {
        try {
            $product = ($this->getProductAction)(UuidV4::fromString($id));
        } catch (ProductNotFound $exception) {
            return $this->buildResponseFromBadRequestException(
                new BadRequestException('id', $exception->getMessage(), 404),
                $response
            );
        }

        return (new ProductResponse($product))->build($response);
    }
}
