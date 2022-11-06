<?php

namespace Tests\Presentation\Http\Controller\Product;

use App\Domain\Model\Product;
use App\Domain\Repository\Product\CreateProductRepository;
use Selective\TestTrait\Traits\HttpJsonTestTrait;
use Selective\TestTrait\Traits\HttpTestTrait;
use Symfony\Component\Uid\UuidV4;
use Tests\AppTestCase;

class GetAllProductsControllerTest extends AppTestCase
{
    use HttpTestTrait;
    use HttpJsonTestTrait;

    public function testItGetsAllProducts()
    {
        $product = new Product(UuidV4::v4(), 'name', 'description', 42.78);
        $anotherProduct = new Product(UuidV4::v4(), 'another name', 'another description', 42.78);
        $createProductRepository = $this->getService(CreateProductRepository::class);
        $createProductRepository->createProduct($product);
        $createProductRepository->createProduct($anotherProduct);

        $request = $this->createJsonRequest('GET', '/api/products');
        $response = $this->executeRequestAndParseResponse($request);

        $toArray = fn(Product $p) => [
            'id' => $p->id->jsonSerialize(),
            'name' => $p->name,
            'description' => $p->description,
            'price' => $p->price
        ];

        self::assertEquals(
            [
                $toArray($anotherProduct),
                $toArray($product)
            ],
            $response['data']
        );
    }

    public function testItReturnsEmptyArrayWhenThereAreNoProducts()
    {
        $request = $this->createJsonRequest('GET', '/api/products');
        $response = $this->executeRequestAndParseResponse($request);

        self::assertEquals(
            [],
            $response['data']
        );
    }
}
