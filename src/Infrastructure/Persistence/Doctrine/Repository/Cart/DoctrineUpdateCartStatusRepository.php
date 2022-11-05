<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository\Cart;

use App\Domain\Model\Cart;
use App\Domain\Repository\Cart\UpdateCartStatusRepository;
use Doctrine\ORM\EntityManager;

class DoctrineUpdateCartStatusRepository implements UpdateCartStatusRepository
{
    public function __construct(
        private readonly EntityManager $entityManager
    ) {}

    public function update(Cart $cart): void
    {
        $this->entityManager->flush();
    }
}
