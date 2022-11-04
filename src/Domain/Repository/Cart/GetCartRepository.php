<?php

namespace App\Domain\Repository\Cart;

use App\Domain\Exception\CartNotFound;
use App\Domain\Model\Cart;
use Symfony\Component\Uid\UuidV4;

interface GetCartRepository
{
    /**
     * @param UuidV4 $id
     * @return Cart
     * @throws CartNotFound
     */
    public function getCart(UuidV4 $id): Cart;
}
