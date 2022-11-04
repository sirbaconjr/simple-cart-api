<?php

namespace App\Presentation\Http\Request\Product;

use App\Presentation\Http\Request\Request;

class ProductRequest extends Request
{
    public readonly string $name;

    public readonly string $description;

    public readonly float $price;

    protected function setup(array $body): void
    {
        $this->name = $this->getKeyOrThrow($body, 'name');
        $this->description = $this->getKeyOrThrow($body, 'description');
        $this->price = $this->getKeyOrThrow($body, 'price');
    }

    private function getKeyOrThrow(array $body, string $key): mixed
    {
        return $body[$key] ?? throw new \Exception('Invalid key ' . $key);
    }
}
