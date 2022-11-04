<?php

namespace App\Infrastructure\Persistence\Doctrine\Repositry\Cart;

use App\Domain\Enum\CartStatus;
use App\Domain\Model\Cart;
use App\Domain\Repository\UpdateCartStatusRepository;
use Doctrine\ORM\EntityManager;

class DoctrineUpdateCartStatusRepository implements UpdateCartStatusRepository
{
    public function __construct(
        private readonly EntityManager $entityManager
    ) {}

    public function update(Cart $cart, CartStatus $status): void
    {
        $this->entityManager->getRepository(Cart::class)
            ->createQueryBuilder('c')
            ->update('c.status', $status)
            ->getQuery()
            ->execute();
    }
}
