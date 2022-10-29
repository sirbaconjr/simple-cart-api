<?php

namespace App\Application\AddProductToCart;

use App\Domain\Exception\InvalidAmountException;
use App\Domain\Exception\ProductNotFound;
use App\Domain\Model\Cart;
use App\Domain\Model\CartItem;
use App\Domain\Repository\CreateCartItemRepository;
use App\Domain\Repository\GetProductRepository;
use App\Domain\Validator\CartItemValidator;
use Symfony\Component\Uid\UuidV4;

class AddProductToCartAction
{
    public function __construct(
        private readonly CreateCartItemRepository $createCartItemRepository,
        private readonly GetProductRepository $getProductRepository
    )
    {
    }

    /**
     * @param Cart $cart
     * @param UuidV4 $productId
     * @param float $amount
     * @return Cart
     * @throws ProductNotFound
     * @throws InvalidAmountException
     */
    public function __invoke(Cart $cart, UuidV4 $productId, float $amount): Cart
    {
        CartItemValidator::validateAmount($amount);

        $product = $this->getProductRepository->getProduct($productId);

        $cartItem = new CartItem($cart, $product, $amount);

        $this->createCartItemRepository->create($cartItem);

        return $cart;
    }
}
