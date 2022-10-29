<?php

namespace App\Domain\Validator;

use App\Domain\Exception\InvalidAmountException;

class CartItemValidator
{
    const MINIMAL_AMOUNT = 1;

    /**
     * @param float $amount
     * @return void
     * @throws InvalidAmountException
     */
    public static function validateAmount(float $amount): void
    {
        if ($amount < self::MINIMAL_AMOUNT) {
            throw new InvalidAmountException($amount);
        }
    }
}
