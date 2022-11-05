<?php

namespace Tests\Infrastructure\Persistence\Doctrine\Repository\Product;

use App\Domain\Model\Product;
use App\Infrastructure\Persistence\Doctrine\Repository\Product\DoctrineCreateProductRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\Product\DoctrineDeleteProductRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\Product\DoctrineGetAllProductsRepository;
use Symfony\Component\Uid\UuidV4;
use Tests\AppTestCase;

class DoctrineDeleteProductRepositoryTest extends AppTestCase
{
    public function testDeleteProduct()
    {
        $createProductRepository = $this->getService(DoctrineCreateProductRepository::class);
        $deleteProductRepository = $this->getService(DoctrineDeleteProductRepository::class);
        $getAllProductsRepository = $this->getService(DoctrineGetAllProductsRepository::class);

        $product = new Product(UuidV4::v4(), "Name", "Description", 42.78);
        $createProductRepository->createProduct($product);

        $deleteProductRepository->deleteProduct($product->id);

        $products = $getAllProductsRepository->getAll();

        self::assertEquals([], $products);
    }
}
