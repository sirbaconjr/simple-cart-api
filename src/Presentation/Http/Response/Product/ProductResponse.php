<?php

namespace App\Presentation\Http\Response\Product;

use App\Domain\Model\Product;
use App\Presentation\Http\Response\Response;

class ProductResponse extends Response
{
    public function __construct(
        private readonly Product $product
    )
    {
    }

    protected function getData(): array
    {
        return [
            'id' => $this->product->id,
            'name' => $this->product->name,
            'description' => $this->product->description,
            'price' => $this->product->price
        ];
    }

    protected function getStatusCode(): int
    {
        return 200;
    }
}
