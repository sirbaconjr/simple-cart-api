<?php

namespace Tests\Domain\Model;

use App\Domain\Enum\CartStatus;
use App\Domain\Model\Cart;
use App\Domain\Model\CartItem;
use App\Domain\Model\Product;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV4;

class CartTest extends TestCase
{
    public function testTotalReturnZeroForEmptyCart()
    {
        $cart = new Cart(UuidV4::v4(), [], CartStatus::New);

        self::assertEquals(
            0,
            $cart->total()
        );
    }

    public function testTotalIsCorrect()
    {
        $product = new Product(UuidV4::v4(), 'name', 'description', 2);
        $anotherProduct = new Product(UuidV4::v4(), 'another name', 'another description', 1.3);
        $cart = new Cart(UuidV4::v4(), [], CartStatus::New);
        $cart->items[] = new CartItem($cart, $product, 1);
        $cart->items[] = new CartItem($cart, $anotherProduct, 2);

        self::assertEquals(
            4.6,
            $cart->total()
        );
    }
}
