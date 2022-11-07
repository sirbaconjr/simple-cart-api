<?php

namespace Tests\Infrastructure\Persistence\Doctrine\Repository\CartItem;

use App\Domain\Enum\CartStatus;
use App\Domain\Model\Cart;
use App\Domain\Model\CartItem;
use App\Domain\Model\Product;
use App\Infrastructure\Persistence\Doctrine\Repository\Cart\DoctrineCreateCartRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\Cart\DoctrineGetCartRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\CartItem\DoctrineCreateCartItemRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\Product\DoctrineCreateProductRepository;
use Symfony\Component\Uid\UuidV4;
use Tests\AppTestCase;

class DoctrineCreateCartItemRepositoryTest extends AppTestCase
{
    public function testItCreatesACartItem()
    {
        $createCartRepository = $this->getService(DoctrineCreateCartRepository::class);
        $createProductRepository = $this->getService(DoctrineCreateProductRepository::class);
        $createCartItemRepository = $this->getService(DoctrineCreateCartItemRepository::class);
        $getCartRepository = $this->getService(DoctrineGetCartRepository::class);

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
