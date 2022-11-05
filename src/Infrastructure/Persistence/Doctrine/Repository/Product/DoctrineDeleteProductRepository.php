<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository\Product;

use App\Domain\Exception\ProductNotFound;
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
        $repo = $this->entityManager->getRepository(Product::class);

        $product = $repo->find($id);

        if (!$product) {
            throw new ProductNotFound($id);
        }

        $this->entityManager->remove($product);
        $this->entityManager->flush();

        $this->entityManager->clear(Product::class);
    }
}
