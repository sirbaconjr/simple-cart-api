<?php

namespace App\Domain\Repository;

use App\Domain\Enum\CartStatus;
use App\Domain\Model\Cart;

interface UpdateCartStatusRepository
{
    /**
     * @param Cart $cart
     * @param CartStatus $status
     * @return void
     */
    public function update(Cart $cart, CartStatus $status): void;
}
