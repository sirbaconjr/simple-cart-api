<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Model\Cart;
use Doctrine\ORM\EntityManager;

class DoctrineCreateCartRepository implements \App\Domain\Repository\CreateCartRepository
{
    public function __construct(
        private readonly EntityManager $entityManager
    ) {}

    public function createCart(Cart $cart): void
    {
        $this->entityManager->persist($cart);
        $this->entityManager->flush();
    }
}
