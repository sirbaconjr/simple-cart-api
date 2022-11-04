<?php

namespace Tests\Infrastructure\Persistence\Doctrine\Repositry\Product;

use App\Domain\Model\Product;
use App\Infrastructure\Persistence\Doctrine\Repositry\Product\DoctrineCreateProductRepository;
use App\Infrastructure\Persistence\Doctrine\Repositry\Product\DoctrineGetProductRepository;
use App\Infrastructure\Persistence\Doctrine\Repositry\Product\DoctrineUpdateProductRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV4;
use Tests\AppTestCase;

class DoctrineUpdateProductRepositoryTest extends AppTestCase
{
    public function testItUpdatesAProduct()
    {
        $createProductRepository = $this->container(DoctrineCreateProductRepository::class);
        $getProductRepository = $this->container(DoctrineGetProductRepository::class);
        $updateProductRepository = $this->container(DoctrineUpdateProductRepository::class);

        $product = new Product(UuidV4::v4(), "Name", "Description", 42.78);
        $createProductRepository->createProduct($product);

        $updatedProduct = new Product($product->id, "Another Name", "Another Description", 78.42);

        $updateProductRepository->update($updatedProduct);

        $foundProduct = $getProductRepository->getProduct($product->id);

        self::assertEquals($updatedProduct, $foundProduct);
    }
}
