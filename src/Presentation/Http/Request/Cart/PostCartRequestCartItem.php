<?php

namespace App\Presentation\Http\Request\Cart;

use Symfony\Component\Uid\UuidV4;

class PostCartRequestCartItem
{
    public function __construct(
        public readonly UuidV4 $id,
        public readonly int $amount
    )
    {
    }
}
