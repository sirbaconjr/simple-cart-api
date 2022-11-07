<?php

namespace App\Domain\Model;

use App\Domain\Enum\CartStatus;
use Symfony\Component\Uid\UuidV4;

class Cart
{
    /**
     * @param UuidV4 $id
     * @param CartItem[] $items
     * @param CartStatus $status
     * @param User|null $user
     */
    public function __construct(
        public readonly UuidV4 $id,
        public iterable $items,
        public CartStatus $status,
        public ?User $user = null
    ) {}

    public function total(): float
    {
        $total = 0;

        foreach ($this->items as $item) {
            $total += $item->amount * $item->product->price;
        }

        return $total;
    }
}
