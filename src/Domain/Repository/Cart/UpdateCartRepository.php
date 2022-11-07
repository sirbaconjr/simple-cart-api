<?php

namespace App\Domain\Repository\Cart;

use App\Domain\Model\Cart;

interface UpdateCartRepository
{
    /**
     * @param Cart $cart
     * @return void
     */
    public function update(Cart $cart): void;
}
