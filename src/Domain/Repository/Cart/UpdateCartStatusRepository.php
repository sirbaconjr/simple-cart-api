<?php

namespace App\Domain\Repository\Cart;

use App\Domain\Enum\CartStatus;
use App\Domain\Model\Cart;

interface UpdateCartStatusRepository
{
    /**
     * @param Cart $cart
     * @return void
     */
    public function update(Cart $cart): void;
}
