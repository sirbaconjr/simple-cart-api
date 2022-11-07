<?php

namespace Tests\Presentation\Http\Controller\Product;

use App\Domain\Model\Product;
use App\Domain\Repository\Product\CreateProductRepository;
use Symfony\Component\Uid\UuidV4;
use Tests\AppTestCase;

class GetProductControllerTest extends AppTestCase
{
    public function testItGetsAProduct()
    {
        $product = new Product(UuidV4::v4(), 'name', 'description', 42.78);
        $createProductRepository = $this->getService(CreateProductRepository::class);
        $createProductRepository->createProduct($product);

        $request = $this->createJsonRequest('GET', "/api/products/{$product->id->jsonSerialize()}");
        $response = $this->executeRequestAndParseResponse($request);

        self::assertEquals(
            [
                'id' => $product->id->jsonSerialize(),
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price
            ],
            $response['data']
        );
    }

    public function testItThrowsProductNotFoundExceptionWhenProductDoesNotExist()
    {
        $id = UuidV4::v4();
        $request = $this->createJsonRequest('GET', "/api/products/{$id->jsonSerialize()}");
        $response = $this->executeRequestAndParseResponse($request);

        self::assertEquals(
            [],
            $response['data']
        );

        self::assertEquals(
            [
                'id' => "Product with ID '{$id->jsonSerialize()}' not found"
            ],
            $response['errors']
        );
    }
}
