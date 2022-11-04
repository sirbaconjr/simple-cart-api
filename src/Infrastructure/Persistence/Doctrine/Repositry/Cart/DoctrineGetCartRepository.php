<?php

namespace App\Infrastructure\Persistence\Doctrine\Repositry\Cart;

use App\Domain\Exception\CartNotFound;
use App\Domain\Model\Cart;
use App\Domain\Repository\Cart\GetCartRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Uid\UuidV4;

class DoctrineGetCartRepository implements GetCartRepository
{
    public function __construct(
        private readonly EntityManager $entityManager
    ) {}

    public function getCart(UuidV4 $id): Cart
    {
        $cart = $this->entityManager
            ->getRepository(Cart::class)
            ->find($id);

        if ($cart == null) {
            throw new CartNotFound($id);
        }

        return $cart;
    }
}
