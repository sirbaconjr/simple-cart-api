<?php

namespace App\Application\GetCart;

use App\Domain\Enum\CartStatus;
use App\Domain\Exception\CartNotFound;
use App\Domain\Helper\SessionKey;
use App\Domain\Model\Cart;
use App\Domain\Repository\Cart\CreateCartRepository;
use App\Domain\Repository\Cart\GetCartRepository;
use App\Domain\Repository\Session\SessionRepository;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

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

        if ($sessionCartId != null) {
            $sessionCartId = UuidV4::fromString($sessionCartId);
        }

        $sessionCart = $this->tryToGetCart($sessionCartId);

        if ($sessionCart) {
            return $sessionCart;
        }

        $cart = $this->createNewCart();

        $this->sessionRepository->set(SessionKey::Cart, $cart->id->jsonSerialize());

        return $cart;
    }

    private function tryToGetCart(UuidV4 $id): ?Cart
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
            [],
            CartStatus::New,
        );
    }
}
