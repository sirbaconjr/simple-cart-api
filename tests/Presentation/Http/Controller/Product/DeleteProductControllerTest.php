<?php

namespace Tests\Presentation\Http\Controller\Product;

use App\Domain\Exception\ProductNotFound;
use App\Domain\Model\Product;
use App\Domain\Repository\Product\CreateProductRepository;
use App\Domain\Repository\Product\GetProductRepository;
use Selective\TestTrait\Traits\HttpJsonTestTrait;
use Selective\TestTrait\Traits\HttpTestTrait;
use Symfony\Component\Uid\UuidV4;
use Tests\AppTestCase;

class DeleteProductControllerTest extends AppTestCase
{
    use HttpTestTrait;
    use HttpJsonTestTrait;

    public function testItDeletesAProduct()
    {
        $product = new Product(UuidV4::v4(), 'name', 'description', 42.78);
        $this->getService(CreateProductRepository::class)->createProduct($product);

        $request = $this->createJsonRequest('DELETE', "/api/products/{$product->id->jsonSerialize()}");
        $response = $this->executeRequestAndParseResponse($request);

        $this->assertEquals(
            [
                'data' => [
                    'success' => true
                ],
                'errors' => []
            ],
            $response
        );

        self::expectException(ProductNotFound::class);
        $this->getService(GetProductRepository::class)->getProduct($product->id);
    }

    public function testItThrowsProductNotFoundWhenProductDoesNotExist()
    {
        $product = new Product(UuidV4::v4(), 'name', 'description', 42.78);

        $request = $this->createJsonRequest('DELETE', "/api/products/{$product->id->jsonSerialize()}");
        $response = $this->app->handle($request);

        self::assertEquals(
            [
                'data' => [],
                'errors' => [
                    'id' => "Product with ID '{$product->id->jsonSerialize()}' not found"
                ]
            ],
            json_decode((string) $response->getBody(), true)
        );

        self::assertEquals(
            404,
            $response->getStatusCode()
        );
    }
}
