<?php

namespace App\Domain\Model;

use Symfony\Component\Uid\UuidV4;

class Cart
{
    /**
     * @param UuidV4 $id
     * @param CartItem[] $items
     */
    public function __construct(
        public readonly UuidV4 $id,
        public readonly array $items
    ) {}
}
