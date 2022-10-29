<?php

namespace App\Domain\Exception;

use Symfony\Component\Uid\UuidV4;

class CartNotFound extends \Exception
{
    public function __construct(UuidV4 $cartId)
    {
        parent::__construct(
            message: "Cart with ID '$cartId' not found"
        );
    }
}
