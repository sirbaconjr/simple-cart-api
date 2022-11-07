<?php

namespace App\Application;

use App\Domain\Enum\CartStatus;
use App\Domain\Exception\CartAlreadyBoughtException;
use App\Domain\Exception\EmptyCartException;
use App\Domain\Model\Cart;
use App\Domain\Model\User;
use App\Domain\Repository\Cart\ScheduleCartCheckoutEmailRepository;
use App\Domain\Repository\Cart\UpdateCartRepository;
use App\Domain\Validator\CartValidator;

class CheckoutCartAction
{
    public function __construct(
        private readonly UpdateCartRepository                $updateRepository,
        private readonly ScheduleCartCheckoutEmailRepository $scheduleCartCheckoutEmailRepository
    )
    {
    }

    /**
     * @param Cart $cart
     * @param User $user
     * @return Cart
     * @throws CartAlreadyBoughtException
     * @throws EmptyCartException
     */
    public function __invoke(Cart $cart, User $user): Cart
    {
        CartValidator::isNotBought($cart);

        CartValidator::isNotEmpty($cart);

        $cart->status = CartStatus::Bought;

        $cart->user = $user;

        $this->updateRepository->update($cart);

        $this->scheduleCartCheckoutEmailRepository->schedule($cart);

        return $cart;
    }
}
