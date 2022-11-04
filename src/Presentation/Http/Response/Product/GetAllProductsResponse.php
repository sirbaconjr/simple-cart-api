<?php

namespace App\Presentation\Http\Response\Product;

use App\Domain\Model\Product;
use App\Presentation\Http\Response\Response;

class GetAllProductsResponse extends Response
{
    public function __construct(
        private readonly array $products
    )
    {
    }

    protected function getData(): array
    {
        return array_map(function (Product $product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price
            ];
        }, $this->products);
    }

    protected function getStatusCode(): int
    {
        return 200;
    }
}
