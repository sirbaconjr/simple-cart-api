<?php

namespace App\Domain\Validator;

use App\Domain\Enum\CartStatus;
use App\Domain\Exception\CartAlreadyBoughtException;
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
        if ($cart->status == CartStatus::Bought) {
            throw new CartAlreadyBoughtException($cart);
        }
    }
}
