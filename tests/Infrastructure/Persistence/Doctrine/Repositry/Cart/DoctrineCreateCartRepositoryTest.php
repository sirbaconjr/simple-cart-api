<?php

namespace Tests\Infrastructure\Persistence\Doctrine\Repositry\Cart;

use App\Domain\Enum\CartStatus;
use App\Domain\Model\Cart;
use App\Infrastructure\Persistence\Doctrine\Repositry\Cart\DoctrineCreateCartRepository;
use App\Infrastructure\Persistence\Doctrine\Repositry\Cart\DoctrineGetCartRepository;
use Symfony\Component\Uid\UuidV4;
use Tests\AppTestCase;

class DoctrineCreateCartRepositoryTest extends AppTestCase
{
    public function testItCreatesACart()
    {
        $createCartRepository = $this->container(DoctrineCreateCartRepository::class);
        $getCartRepository = $this->container(DoctrineGetCartRepository::class);

        $cart = new Cart(UuidV4::v4(), [], CartStatus::New);
        $createCartRepository->createCart($cart);

        $foundCart = $getCartRepository->getCart($cart->id);

        self::assertEquals($cart, $foundCart);
    }
}
