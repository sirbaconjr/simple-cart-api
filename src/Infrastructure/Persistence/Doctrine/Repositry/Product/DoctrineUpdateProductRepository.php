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
        $this->entityManager
            ->createQueryBuilder()
            ->update(Product::class, 'p')
            ->set('p.name', ':name')
            ->set('p.description', ':description')
            ->set('p.price', ':price')
            ->where('p.id = :id')
            ->setParameters([
                'id' => $product->id->toBinary(),
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price
            ])
            ->getQuery()
            ->execute();

        $this->entityManager->clear(Product::class);
    }
}
