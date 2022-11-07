<?php

namespace Tests\Domain\Validator;

use App\Domain\Enum\CartStatus;
use App\Domain\Exception\CartAlreadyBoughtException;
use App\Domain\Exception\EmptyCartException;
use App\Domain\Model\Cart;
use App\Domain\Validator\CartValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class CartValidatorTest extends TestCase
{
    public function testItValidatesACartCanReceiveItems()
    {
        $cart = new Cart(Uuid::v4(), [], CartStatus::New);
        CartValidator::canReceiveItems($cart);

        $cart->status = CartStatus::Bought;
        self::expectException(CartAlreadyBoughtException::class);
        CartValidator::canReceiveItems($cart);
    }

    public function testItValidatesACartIsEmpty()
    {
        $cart = new Cart(Uuid::v4(), [], CartStatus::New);

        self::expectException(EmptyCartException::class);
        CartValidator::isNotEmpty($cart);
    }
}
