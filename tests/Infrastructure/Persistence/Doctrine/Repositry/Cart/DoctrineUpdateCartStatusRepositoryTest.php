<?php

namespace Tests\Infrastructure\Persistence\Doctrine\Repositry\Cart;

use App\Domain\Enum\CartStatus;
use App\Domain\Model\Cart;
use App\Domain\Repository\Cart\GetCartRepository;
use App\Infrastructure\Persistence\Doctrine\Repositry\Cart\DoctrineCreateCartRepository;
use App\Infrastructure\Persistence\Doctrine\Repositry\Cart\DoctrineGetCartRepository;
use App\Infrastructure\Persistence\Doctrine\Repositry\Cart\DoctrineUpdateCartStatusRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV4;
use Tests\AppTestCase;

class DoctrineUpdateCartStatusRepositoryTest extends AppTestCase
{
    public function testItUpdatesTheCartStatus()
    {
        $createCartRepository = $this->container(DoctrineCreateCartRepository::class);
        $updateCartStatusRepository = $this->container(DoctrineUpdateCartStatusRepository::class);
        $getCartRepository = $this->container(DoctrineGetCartRepository::class);

        $cart = new Cart(UuidV4::v4(), [], CartStatus::New);
        $createCartRepository->createCart($cart);

        $cart->status = CartStatus::Bought;

        $updateCartStatusRepository->update($cart);

        $foundCart = $getCartRepository->getCart($cart->id);

        self::assertEquals($cart, $foundCart);
    }
}
