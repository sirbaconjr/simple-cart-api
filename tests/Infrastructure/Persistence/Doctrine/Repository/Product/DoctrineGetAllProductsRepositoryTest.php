<?php

namespace Tests\Infrastructure\Persistence\Doctrine\Repository\Product;

use App\Domain\Model\Product;
use App\Infrastructure\Persistence\Doctrine\Repository\Product\DoctrineCreateProductRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\Product\DoctrineGetAllProductsRepository;
use Symfony\Component\Uid\UuidV4;
use Tests\AppTestCase;

class DoctrineGetAllProductsRepositoryTest extends AppTestCase
{
    public function testItGetAllProducts()
    {
        $createProductRepository = $this->getService(DoctrineCreateProductRepository::class);
        $getAllProductsRepository = $this->getService(DoctrineGetAllProductsRepository::class);

        $product = new Product(UuidV4::v4(), "Name", "Description", 42.78);
        $anotherProduct = new Product(UuidV4::v4(), "Another Name", "Another Description", 78.42);

        $createProductRepository->createProduct($product);
        $createProductRepository->createProduct($anotherProduct);

        $allProducts = $getAllProductsRepository->getAll();

        self::assertEquals([$anotherProduct, $product], $allProducts);
    }
}
