<?php

namespace App\Domain\Model;

class CartItem
{
    /**
     * @param Cart $cart
     * @param Product $product
     * @param int $amount
     */
    public function __construct(
        public readonly Cart $cart,
        public Product $product,
        public int $amount
    ) {}
}
