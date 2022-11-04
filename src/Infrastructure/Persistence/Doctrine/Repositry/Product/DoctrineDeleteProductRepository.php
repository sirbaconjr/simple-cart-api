<?php

namespace App\Infrastructure\Persistence\Doctrine\Repositry\Product;

use App\Domain\Model\Product;
use App\Domain\Repository\Product\DeleteProductRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Uid\UuidV4;

class DoctrineDeleteProductRepository implements DeleteProductRepository
{
    public function __construct(
        private readonly EntityManager $entityManager
    ) {}

    public function deleteProduct(UuidV4 $id): void
    {
        $this->entityManager->getRepository(Product::class)
            ->createQueryBuilder('p')
            ->delete()
            ->where('p.id', $id)
            ->getQuery()
            ->execute();
    }
}
