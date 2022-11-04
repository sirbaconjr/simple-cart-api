<?php

namespace App\Presentation\Http\Request\Cart;

use App\Presentation\Http\Request\Request;
use Symfony\Component\Uid\UuidV4;

class PostCartRequest extends Request
{
    /**
     * @var PostCartRequestCartItem[]
     */
    public array $items = [];

    protected function setup(array $body): void
    {
        foreach ($body as $item) {
            $this->items[] = new PostCartRequestCartItem(
                $this->getId($item),
                $this->getAmount($item)
            );
        }
    }

    private function getId(array $item): UuidV4
    {
        if (!array_key_exists('id', $item) || !UuidV4::isValid($item['id'])) {
            throw new \Exception('1');
        }

        return UuidV4::fromString($item['id']);
    }

    private function getAmount(array $item): int
    {
        if (!array_key_exists('amount', $item) || !is_numeric($item['amount']) || !is_int($item['amount'])) {
            throw new \Exception('2');
        }

        return $item['amount'];
    }
}
