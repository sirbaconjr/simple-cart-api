<?php

namespace Tests\Infrastructure\Persistence\Doctrine\Repository\Cart;

use App\Domain\Enum\CartStatus;
use App\Domain\Model\Cart;
use App\Infrastructure\Persistence\Doctrine\Repository\Cart\DoctrineCreateCartRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\Cart\DoctrineGetCartRepository;
use Symfony\Component\Uid\UuidV4;
use Tests\AppTestCase;

class DoctrineGetCartRepositoryTest extends AppTestCase
{
    public function testItGetsACart()
    {
        $createCartRepository = $this->getService(DoctrineCreateCartRepository::class);
        $getCartRepository = $this->getService(DoctrineGetCartRepository::class);

        $cart = new Cart(UuidV4::v4(), [], CartStatus::New);
        $createCartRepository->createCart($cart);

        $foundCart = $getCartRepository->getCart($cart->id);

        self::assertEquals($cart, $foundCart);
    }
}
