<?php

namespace App\Domain\Repository\Cart;

use App\Domain\Model\Cart;

interface ScheduleCartCheckoutEmailRepository
{
    /**
     * @param Cart $cart
     * @return void
     */
    public function schedule(Cart $cart): void;
}
