<?php

namespace Tests\Application;

use App\Application\AddProductToCartAction;
use App\Domain\Enum\CartStatus;
use App\Domain\Exception\CartAlreadyBoughtException;
use App\Domain\Exception\InvalidAmountException;
use App\Domain\Model\Cart;
use App\Domain\Model\CartItem;
use App\Domain\Model\Product;
use App\Domain\Repository\CartItem\CreateCartItemRepository;
use App\Domain\Repository\Product\GetProductRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV4;
use Tests\AppTestCase;

class AddProductToCartActionTest extends AppTestCase
{
    private CreateCartItemRepository $createCartItemRepository;
    private GetProductRepository $getProductRepository;
    private AddProductToCartAction $addProductToCartAction;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createCartItemRepository = self::createMock(CreateCartItemRepository::class);
        $this->getProductRepository = self::createMock(GetProductRepository::class);
        $this->addProductToCartAction = new AddProductToCartAction(
            $this->createCartItemRepository,
            $this->getProductRepository
        );
    }

    public function testItThrowsCartAlreadyBoughtExceptionWhenCartIsAlreadyBought()
    {
        $cart = new Cart(UuidV4::v4(), [], CartStatus::Bought);

        self::expectException(CartAlreadyBoughtException::class);

        ($this->addProductToCartAction)($cart, UuidV4::v4(), 1);
    }

    public function testItThrowsInvalidAmountExceptionWhenAmountIsInvalid()
    {
        $cart = new Cart(UuidV4::v4(), [], CartStatus::New);

        self::expectException(InvalidAmountException::class);

        ($this->addProductToCartAction)($cart, UuidV4::v4(), 0);
    }

    public function testItAddsProductToCart()
    {
        $cart = new Cart(UuidV4::v4(), [], CartStatus::New);
        $product = new Product(UuidV4::v4(), 'name', 'description', 2);
        $amount = 1;

        $this->getProductRepository
            ->expects(self::once())
            ->method('getProduct')
            ->with($product->id)
            ->willReturn($product);

        $this->createCartItemRepository
            ->expects(self::once())
            ->method('create')
            ->with(self::callback(
                function (CartItem $cartItem) use ($cart, $product, $amount) {
                    return $cartItem->cart === $cart && $cartItem->product === $product && $cartItem->amount === $amount;
                }
            ));

        $result = ($this->addProductToCartAction)($cart, $product->id, $amount);

        self::assertEquals($cart, $result);
    }
}
