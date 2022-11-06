<?php

namespace App\Presentation\Http\Request\Product;

use App\Presentation\Http\Exception\BadRequestException;
use App\Presentation\Http\Request\Request;

class ProductRequest extends Request
{
    public readonly string $name;

    public readonly string $description;

    public readonly float $price;

    /**
     * @param array $body
     * @return void
     * @throws BadRequestException
     */
    protected function setup(array $body): void
    {
        $this->name = $this->getStringFromBody($body, 'name');
        $this->description = $this->getStringFromBody($body, 'description');
        $this->price = $this->getPrice($body);
    }

    /**
     * @param array $body
     * @param string $key
     * @return string
     * @throws BadRequestException
     */
    private function getStringFromBody(array $body, string $key): string
    {
        if (!array_key_exists($key, $body) || !is_string($body[$key]) || empty($body[$key])) {
            throw new BadRequestException($key, "The $key field must be a non-empty string");
        }

        return $body[$key];
    }

    /**
     * @param array $body
     * @return float
     * @throws BadRequestException
     */
    private function getPrice(array $body): float
    {
        if (!array_key_exists('price', $body) || !is_numeric($body['price'])) {
            throw new BadRequestException('price', "The price field must be a number");
        }

        return floatval($body['price']);
    }
}
