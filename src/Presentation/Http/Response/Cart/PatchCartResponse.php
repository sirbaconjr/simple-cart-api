<?php

namespace App\Presentation\Http\Response\Cart;

use App\Domain\Model\Cart;
use App\Presentation\Http\Response\Response;

class PatchCartResponse extends Response
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
            'status' => $this->cart->status->value
        ];
    }
}
