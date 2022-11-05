<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository\Product;

use App\Domain\Model\Product;
use App\Domain\Repository\Product\CreateProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;

class DoctrineCreateProductRepository implements CreateProductRepository
{
    public function __construct(
        private readonly EntityManager $entityManager
    ) {}

    /**
     * @param Product $product
     * @return void
     * @throws ORMException
     */
    public function createProduct(Product $product): void
    {
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }
}
