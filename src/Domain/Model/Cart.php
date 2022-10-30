<?php

namespace App\Domain\Model;

use App\Domain\Enum\CartStatus;
use Symfony\Component\Uid\UuidV4;

class Cart
{
    /**
     * @param UuidV4 $id
     * @param array $items
     * @param CartStatus $status
     */
    public function __construct(
        public readonly UuidV4 $id,
        public readonly array $items,
        public readonly CartStatus $status
    ) {}
}
