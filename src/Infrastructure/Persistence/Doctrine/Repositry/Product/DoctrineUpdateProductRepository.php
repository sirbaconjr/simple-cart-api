<?php

namespace App\Infrastructure\Persistence\Doctrine\Repositry\Product;

use App\Domain\Model\Product;
use App\Domain\Repository\Product\UpdateProductRepository;
use Doctrine\ORM\EntityManager;

class DoctrineUpdateProductRepository implements UpdateProductRepository
{
    public function __construct(
        private readonly EntityManager $entityManager
    ) {}

    public function update(Product $product): void
    {
        $this->entityManager->flush();
    }
}
