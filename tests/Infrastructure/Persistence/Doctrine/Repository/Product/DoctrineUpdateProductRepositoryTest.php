<?php

namespace Tests\Infrastructure\Persistence\Doctrine\Repository\Product;

use App\Domain\Model\Product;
use App\Infrastructure\Persistence\Doctrine\Repository\Product\DoctrineCreateProductRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\Product\DoctrineGetProductRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\Product\DoctrineUpdateProductRepository;
use Symfony\Component\Uid\UuidV4;
use Tests\AppTestCase;

class DoctrineUpdateProductRepositoryTest extends AppTestCase
{
    public function testItUpdatesAProduct()
    {
        $createProductRepository = $this->getService(DoctrineCreateProductRepository::class);
        $getProductRepository = $this->getService(DoctrineGetProductRepository::class);
        $updateProductRepository = $this->getService(DoctrineUpdateProductRepository::class);

        $product = new Product(UuidV4::v4(), "Name", "Description", 42.78);
        $createProductRepository->createProduct($product);

        $product->name = "New Name";

        $updateProductRepository->update($product);

        $foundProduct = $getProductRepository->getProduct($product->id);

        self::assertEquals($product, $foundProduct);
    }
}
