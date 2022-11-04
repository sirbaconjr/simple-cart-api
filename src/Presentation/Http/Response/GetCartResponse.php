<?php

namespace App\Presentation\Http\Response;

use App\Domain\Model\Cart;

class GetCartResponse extends Response
{
    public function __construct(private readonly Cart $cart) {}

    protected function getStatusCode(): int
    {
        return 200;
    }

    protected function getData(): array
    {
        return [
            'id' => $this->cart->id,
            'items' => $this->cart->items,
            'status' => $this->cart->status->value
        ];
    }
}
