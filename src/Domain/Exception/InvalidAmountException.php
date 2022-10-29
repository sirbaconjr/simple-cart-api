<?php

namespace App\Domain\Exception;

class InvalidAmountException extends \Exception
{
    public function __construct(float $providedAmount)
    {
        parent::__construct(
            message: "Invalid amount, must be greater than " . $providedAmount
        );
    }
}
