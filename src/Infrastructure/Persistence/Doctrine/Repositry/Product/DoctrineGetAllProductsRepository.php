<?php

namespace App\Infrastructure\Persistence\Doctrine\Repositry\Product;

use App\Domain\Model\Product;
use App\Domain\Repository\Product\GetAllProductsRepository;
use Doctrine\ORM\EntityManager;

class DoctrineGetAllProductsRepository implements GetAllProductsRepository
{
    public function __construct(
        private readonly EntityManager $entityManager
    ) {}

    public function getAll(): array
    {
        return $this->entityManager
            ->getRepository(Product::class)
            ->findAll();
    }
}
