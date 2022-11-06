<?php

namespace App\Domain\Validator;

use App\Domain\Enum\CartStatus;
use App\Domain\Exception\CartAlreadyBoughtException;
use App\Domain\Exception\EmptyCartException;
use App\Domain\Model\Cart;

class CartValidator
{
    /**
     * @param Cart $cart
     * @return void
     * @throws CartAlreadyBoughtException
     */
    public static function canReceiveItems(Cart $cart): void
    {
        self::isNotBought($cart);
    }

    /**
     * @param Cart $cart
     * @return void
     * @throws CartAlreadyBoughtException
     */
    public static function isNotBought(Cart $cart): void
    {
        if ($cart->status == CartStatus::Bought) {
            throw new CartAlreadyBoughtException($cart);
        }
    }

    /**
     * @param Cart $cart
     * @return void
     * @throws EmptyCartException
     */
    public static function isNotEmpty(Cart $cart): void
    {
        if (count($cart->items) == 0) {
            throw new EmptyCartException($cart);
        }
    }
}
