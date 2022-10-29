<?php

namespace App\Domain\Repository;

use App\Domain\Exception\CartNotFound;
use App\Domain\Model\Cart;

interface GetCartRepository
{
    /**
     * @param string $id
     * @return Cart
     * @throws CartNotFound
     */
    public function getCart(string $id): Cart;
}
