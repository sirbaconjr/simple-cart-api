<?php

namespace App\Application;

use App\Domain\Exception\CartAlreadyBoughtException;
use App\Domain\Exception\InvalidAmountException;
use App\Domain\Exception\ProductNotFound;
use App\Domain\Model\Cart;
use App\Domain\Model\CartItem;
use App\Domain\Repository\CartItem\CreateCartItemRepository;
use App\Domain\Repository\Product\GetProductRepository;
use App\Domain\Validator\CartItemValidator;
use App\Domain\Validator\CartValidator;
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
     * @throws InvalidAmountException
     * @throws ProductNotFound
     * @throws CartAlreadyBoughtException
     */
    public function __invoke(Cart $cart, UuidV4 $productId, float $amount): Cart
    {
        CartValidator::canReceiveItems($cart);

        CartItemValidator::validateAmount($amount);

        $product = $this->getProductRepository->getProduct($productId);

        $cartItem = new CartItem($cart, $product, $amount);

        $this->createCartItemRepository->create($cartItem);

        return $cart;
    }
}
