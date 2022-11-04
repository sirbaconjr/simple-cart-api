<?php

namespace App\Infrastructure\Persistence\Doctrine\Repositry\CartItem;

use App\Domain\Model\CartItem;
use App\Domain\Repository\CartItem\CreateCartItemRepository;
use Doctrine\ORM\EntityManager;

class DoctrineCreateCartItemRepository implements CreateCartItemRepository
{
    public function __construct(
        private readonly EntityManager $entityManager
    ) {}

    public function create(CartItem $cartItem): void
    {
        $cartItem->cart->items[] = $cartItem;
        $this->entityManager->persist($cartItem);
        $this->entityManager->flush();
    }
}
