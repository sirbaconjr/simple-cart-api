<?php

namespace Tests\Infrastructure\Persistence\Doctrine\Repositry\Product;

use App\Domain\Model\Product;
use App\Infrastructure\Persistence\Doctrine\Repositry\Product\DoctrineCreateProductRepository;
use App\Infrastructure\Persistence\Doctrine\Repositry\Product\DoctrineGetAllProductsRepository;
use Symfony\Component\Uid\UuidV4;
use Tests\AppTestCase;

class DoctrineGetAllProductsRepositoryTest extends AppTestCase
{
    public function testItGetAllProducts()
    {
        $createProductRepository = $this->container(DoctrineCreateProductRepository::class);
        $getAllProductsRepository = $this->container(DoctrineGetAllProductsRepository::class);

        $product = new Product(UuidV4::v4(), "Name", "Description", 42.78);
        $anotherProduct = new Product(UuidV4::v4(), "Another Name", "Another Description", 78.42);

        $createProductRepository->createProduct($product);
        $createProductRepository->createProduct($anotherProduct);

        $allProducts = $getAllProductsRepository->getAll();

        self::assertEquals([$anotherProduct, $product], $allProducts);
    }
}
