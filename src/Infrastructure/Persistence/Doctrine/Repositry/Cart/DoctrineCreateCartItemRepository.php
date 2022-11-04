<?php

namespace App\Infrastructure\Persistence\Doctrine\Repositry\Cart;

use App\Domain\Model\CartItem;
use App\Domain\Repository\CreateCartItemRepository;
use Doctrine\ORM\EntityManager;

class DoctrineCreateCartItemRepository implements CreateCartItemRepository
{
    public function __construct(
        private readonly EntityManager $entityManager
    ) {}

    public function create(CartItem $cartItem): void
    {
        $this->entityManager->persist($cartItem);
        $this->entityManager->flush();;
    }
}
