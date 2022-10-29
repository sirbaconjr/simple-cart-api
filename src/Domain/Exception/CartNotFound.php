<?php

namespace App\Domain\Exception;

class CartNotFound extends \Exception
{
    public function __construct(string $cartId)
    {
        parent::__construct(
            message: "Cart with ID '$cartId' not found"
        );
    }
}
