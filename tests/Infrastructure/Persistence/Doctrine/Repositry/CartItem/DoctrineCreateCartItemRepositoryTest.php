<?php

namespace Tests\Infrastructure\Persistence\Doctrine\Repositry\CartItem;

use App\Domain\Enum\CartStatus;
use App\Domain\Model\Cart;
use App\Domain\Model\CartItem;
use App\Domain\Model\Product;
use App\Infrastructure\Persistence\Doctrine\Repositry\Cart\DoctrineCreateCartRepository;
use App\Infrastructure\Persistence\Doctrine\Repositry\Cart\DoctrineGetCartRepository;
use App\Infrastructure\Persistence\Doctrine\Repositry\CartItem\DoctrineCreateCartItemRepository;
use App\Infrastructure\Persistence\Doctrine\Repositry\Product\DoctrineCreateProductRepository;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV4;
use Tests\AppTestCase;

class DoctrineCreateCartItemRepositoryTest extends AppTestCase
{
    public function testItCreatesACartItem()
    {
        $createCartRepository = $this->container(DoctrineCreateCartRepository::class);
        $createProductRepository = $this->container(DoctrineCreateProductRepository::class);
        $createCartItemRepository = $this->container(DoctrineCreateCartItemRepository::class);
        $getCartRepository = $this->container(DoctrineGetCartRepository::class);

        $cart = new Cart(UuidV4::v4(), [], CartStatus::New);
        $product = new Product(UuidV4::v4(), 'Name', 'Description', 42.78);
        $cartItem = new CartItem($cart, $product, 2);

        $createCartRepository->createCart($cart);
        $createProductRepository->createProduct($product);
        $createCartItemRepository->create($cartItem);

        $foundCart = $getCartRepository->getCart($cart->id);

        self::assertEquals($cartItem, $foundCart->items->first());
    }
}
