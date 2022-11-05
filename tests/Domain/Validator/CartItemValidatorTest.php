<?php

namespace Tests\Domain\Validator;

use App\Domain\Exception\InvalidAmountException;
use App\Domain\Validator\CartItemValidator;
use PHPUnit\Framework\TestCase;

class CartItemValidatorTest extends TestCase
{
    public function testValidateAmount()
    {
        CartItemValidator::validateAmount(1);
        $this->expectException(InvalidAmountException::class);
        CartItemValidator::validateAmount(0);
    }
}
