<?php

namespace App\Domain\Repository;

use App\Domain\Model\CartItem;

interface CreateCartItemRepository
{
    public function create(CartItem $cartItem): void;
}
