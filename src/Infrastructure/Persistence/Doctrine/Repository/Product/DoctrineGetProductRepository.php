<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository\Product;

use App\Domain\Exception\ProductNotFound;
use App\Domain\Model\Product;
use App\Domain\Repository\Product\GetProductRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Uid\UuidV4;

class DoctrineGetProductRepository implements GetProductRepository
{
    public function __construct(
        private readonly EntityManager $entityManager
    ) {}

    public function getProduct(UuidV4 $id): Product
    {
        $product = $this->entityManager
            ->getRepository(Product::class)
            ->find($id);

        if ($product == null) {
            throw new ProductNotFound($id);
        }

        return $product;
    }
}
