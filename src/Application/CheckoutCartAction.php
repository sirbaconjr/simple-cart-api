<?php

namespace App\Application;

use App\Domain\Enum\CartStatus;
use App\Domain\Exception\CartAlreadyBoughtException;
use App\Domain\Exception\EmptyCartException;
use App\Domain\Model\Cart;
use App\Domain\Repository\Cart\ScheduleCartCheckoutEmailRepository;
use App\Domain\Repository\Cart\UpdateCartStatusRepository;
use App\Domain\Validator\CartValidator;

class CheckoutCartAction
{
    public function __construct(
        private readonly UpdateCartStatusRepository $updateCartStatusRepository,
        private readonly ScheduleCartCheckoutEmailRepository $scheduleCartCheckoutEmailRepository
    )
    {
    }

    /**
     * @param Cart $cart
     * @return Cart
     * @throws EmptyCartException
     * @throws CartAlreadyBoughtException
     */
    public function __invoke(Cart $cart): Cart
    {
        CartValidator::isNotBought($cart);

        CartValidator::isNotEmpty($cart);

        $cart->status = CartStatus::Bought;

        $this->updateCartStatusRepository->update($cart);

        $this->scheduleCartCheckoutEmailRepository->schedule($cart);

        return $cart;
    }
}
