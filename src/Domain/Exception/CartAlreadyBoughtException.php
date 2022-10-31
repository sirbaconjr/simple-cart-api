<?php

namespace App\Domain\Exception;

use App\Domain\Model\Cart;

class CartAlreadyBoughtException extends \Exception
{
    public function __construct(Cart $cart)
    {
        parent::__construct(
            message: "Cart $cart->id already bought"
        );
    }
}
