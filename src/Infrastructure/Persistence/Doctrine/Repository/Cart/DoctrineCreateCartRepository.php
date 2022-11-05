<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository\Cart;

use App\Domain\Model\Cart;
use App\Domain\Repository\Cart\CreateCartRepository;
use Doctrine\ORM\EntityManager;

class DoctrineCreateCartRepository implements CreateCartRepository
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
