<?php

namespace App\Application\GetCart;

use App\Domain\Exception\CartNotFound;
use App\Domain\Helper\SessionKey;
use App\Domain\Model\Cart;
use App\Domain\Repository\CreateCartRepository;
use App\Domain\Repository\GetCartRepository;
use App\Domain\Repository\SessionRepository;
use Symfony\Component\Uid\Uuid;

class GetCartAction
{
    public function __construct(
        private readonly GetCartRepository $getCartRepository,
        private readonly CreateCartRepository $createCartRepository,
        private readonly SessionRepository $sessionRepository
    ) {}

    public function __invoke(): Cart
    {
        $sessionCartId = $this->sessionRepository->get(SessionKey::Cart);

        $sessionCart = $this->tryToGetCart($sessionCartId);

        if ($sessionCart) {
            return $sessionCart;
        }

        $cart = $this->createNewCart();

        $this->sessionRepository->set(SessionKey::Cart, $cart->id->jsonSerialize());

        return $cart;
    }

    private function tryToGetCart(string $id): ?Cart
    {
        try {
            return $this->getCartRepository->getCart($id);
        } catch (CartNotFound) {
            return null;
        }
    }

    private function createNewCart(): Cart
    {
        $cart = $this->buildCart();
        $this->createCartRepository->createCart($cart);

        return $cart;
    }

    private function buildCart(): Cart
    {
        return new Cart(
            Uuid::v4(),
            []
        );
    }
}
