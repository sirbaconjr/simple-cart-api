<?php

namespace App\Domain\Repository\Cart;

use App\Domain\Model\Cart;

interface CreateCartRepository
{
    public function createCart(Cart $cart): void;
}
