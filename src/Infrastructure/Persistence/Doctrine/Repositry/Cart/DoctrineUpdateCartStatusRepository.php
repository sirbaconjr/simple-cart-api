<?php

namespace App\Infrastructure\Persistence\Doctrine\Repositry\Cart;

use App\Domain\Enum\CartStatus;
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
