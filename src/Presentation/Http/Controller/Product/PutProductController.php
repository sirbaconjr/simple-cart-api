<?php

namespace App\Presentation\Http\Controller\Product;

use App\Application\UpdateProductAction;
use App\Domain\Exception\ProductNotFound;
use App\Presentation\Http\Controller\Controller;
use App\Presentation\Http\Exception\BadRequestException;
use App\Presentation\Http\Request\Product\ProductRequest;
use App\Presentation\Http\Response\BooleanResponse;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Symfony\Component\Uid\UuidV4;

class PutProductController extends Controller
{
    public function __construct(
        private readonly UpdateProductAction $updateProductAction
    )
    {
    }


    public function __invoke(Request $request, Response $response, string $id): Response
    {
        $parsedId = UuidV4::fromString($id);

        try {
            $parsedRequest = new ProductRequest($request);
            ($this->updateProductAction)($parsedId, $parsedRequest->name, $parsedRequest->description, $parsedRequest->price);
        } catch (BadRequestException $exception) {
            return $this->buildResponseFromBadRequestException($exception, $response);
        } catch (ProductNotFound $exception) {
            return $this->buildResponseFromAnyException('id', $exception, $response, 404);
        }

        return (new BooleanResponse(true))->build($response);
    }
}
