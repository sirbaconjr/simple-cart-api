<?php

namespace App\Application;

use App\Domain\Enum\CartStatus;
use App\Domain\Exception\EmptyCartException;
use App\Domain\Model\Cart;
use App\Domain\Repository\Cart\UpdateCartStatusRepository;
use App\Domain\Validator\CartValidator;

class CheckoutCartAction
{
    public function __construct(
        private readonly UpdateCartStatusRepository $updateCartStatusRepository
    )
    {
    }

    /**
     * @param Cart $cart
     * @return Cart
     * @throws EmptyCartException
     */
    public function __invoke(Cart $cart): Cart
    {
        CartValidator::isEmpty($cart);

        $this->updateCartStatusRepository->update($cart, CartStatus::Bought);

        return $cart;
    }
}
