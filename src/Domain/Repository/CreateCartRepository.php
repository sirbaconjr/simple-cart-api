<?php

namespace App\Domain\Repository;

use App\Domain\Model\Cart;

interface CreateCartRepository
{
    public function createCart(Cart $cart): void;
}
