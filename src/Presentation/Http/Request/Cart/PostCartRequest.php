<?php

namespace App\Presentation\Http\Request\Cart;

use App\Presentation\Http\Exception\BadRequestException;
use App\Presentation\Http\Request\Request;
use Symfony\Component\Uid\UuidV4;

class PostCartRequest extends Request
{
    /**
     * @var PostCartRequestCartItem[]
     */
    public array $items = [];

    /**
     * @param array $body
     * @return void
     * @throws BadRequestException
     */
    protected function setup(array $body): void
    {
        if (!array_key_exists('items', $body) || !is_array($body['items'])) {
            throw new BadRequestException(
                'items',
                'Items must be a valid array containing products and amounts'
            );
        }

        foreach ($body['items'] as $key => $item) {
            $this->items[] = new PostCartRequestCartItem(
                $this->getId($key, $item),
                $this->getAmount($key, $item)
            );
        }
    }

    /**
     * @param int $key
     * @param array $item
     * @return UuidV4
     * @throws BadRequestException
     */
    private function getId(int $key, array $item): UuidV4
    {
        if (!array_key_exists('id', $item) || !UuidV4::isValid($item['id'])) {
            throw new BadRequestException("items.$key.id", "ID is not a valid UUID4 string");
        }

        return UuidV4::fromString($item['id']);
    }

    /**
     * @param int $key
     * @param array $item
     * @return int
     * @throws BadRequestException
     */
    private function getAmount(int $key, array $item): int
    {
        if (!array_key_exists('amount', $item) || !is_numeric($item['amount']) || !is_int($item['amount'])) {
            throw new BadRequestException("items.$key.id", "Amount is not a number");
        }

        return $item['amount'];
    }
}
