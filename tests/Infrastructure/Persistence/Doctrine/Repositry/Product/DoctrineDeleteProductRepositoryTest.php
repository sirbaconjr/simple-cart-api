<?php

namespace Tests\Infrastructure\Persistence\Doctrine\Repositry\Product;

use App\Domain\Model\Product;
use App\Infrastructure\Persistence\Doctrine\Repositry\Product\DoctrineCreateProductRepository;
use App\Infrastructure\Persistence\Doctrine\Repositry\Product\DoctrineDeleteProductRepository;
use App\Infrastructure\Persistence\Doctrine\Repositry\Product\DoctrineGetAllProductsRepository;
use Symfony\Component\Uid\UuidV4;
use Tests\AppTestCase;

class DoctrineDeleteProductRepositoryTest extends AppTestCase
{
    public function testDeleteProduct()
    {
        $createProductRepository = $this->container(DoctrineCreateProductRepository::class);
        $deleteProductRepository = $this->container(DoctrineDeleteProductRepository::class);
        $getAllProductsRepository = $this->container(DoctrineGetAllProductsRepository::class);

        $product = new Product(UuidV4::v4(), "Name", "Description", 42.78);
        $createProductRepository->createProduct($product);

        $deleteProductRepository->deleteProduct($product->id);

        $products = $getAllProductsRepository->getAll();

        self::assertEquals([], $products);
    }
}
