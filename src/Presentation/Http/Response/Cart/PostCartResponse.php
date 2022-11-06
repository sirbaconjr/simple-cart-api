<?php

namespace App\Presentation\Http\Response\Cart;

use App\Domain\Model\Cart;
use App\Domain\Model\CartItem;
use App\Presentation\Http\Response\Response;

class PostCartResponse extends Response
{
    public function __construct(
        private readonly Cart $cart,
        array $errors
    ) {
        $this->errors = $errors;
    }

    protected function getStatusCode(): int
    {
        return 200;
    }

    protected function getData(): array
    {
        return [
            'id' => $this->cart->id,
            'items' => $this->itemsToArray($this->cart->items),
            'status' => $this->cart->status->value
        ];
    }

    /**
     * @param CartItem[] $items
     * @return array
     */
    private function itemsToArray(iterable $items): array
    {
        $arr = [];

        foreach ($items as $item) {
            $arr[] = [
                'id' => $item->product->id,
                'amount' => $item->amount
            ];
        }

        return $arr;
    }
}
