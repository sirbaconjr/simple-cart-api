<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository\Cart;

use App\Domain\Model\Cart;
use App\Domain\Repository\Cart\UpdateCartRepository;
use Doctrine\ORM\EntityManager;

class DoctrineUpdateCartRepository implements UpdateCartRepository
{
    public function __construct(
        private readonly EntityManager $entityManager
    ) {}

    public function update(Cart $cart): void
    {
        $this->entityManager->flush();
    }
}
