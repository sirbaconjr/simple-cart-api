<?php

namespace Tests\Infrastructure\Persistence\Doctrine\Repository\Product;

use App\Domain\Model\Product;
use App\Infrastructure\Persistence\Doctrine\Repository\Product\DoctrineCreateProductRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\Product\DoctrineGetProductRepository;
use Symfony\Component\Uid\UuidV4;
use Tests\AppTestCase;

class DoctrineGetProductRepositoryTest extends AppTestCase
{
    public function testItGetsProduct()
    {
        $createProductRepository = $this->getService(DoctrineCreateProductRepository::class);
        $getProductRepository = $this->getService(DoctrineGetProductRepository::class);

        $product = new Product(UuidV4::v4(), "Name", "Description", 42.78);
        $createProductRepository->createProduct($product);

        $foundProduct = $getProductRepository->getProduct($product->id);

        self::assertEquals($product, $foundProduct);
    }
}
