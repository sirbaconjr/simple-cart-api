<?php

namespace Tests\Application;

use App\Application\CheckoutCartAction;
use App\Domain\Enum\CartStatus;
use App\Domain\Exception\CartAlreadyBoughtException;
use App\Domain\Exception\EmptyCartException;
use App\Domain\Model\Cart;
use App\Domain\Model\CartItem;
use App\Domain\Model\Product;
use App\Domain\Repository\Cart\UpdateCartStatusRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV4;

class CheckoutCartActionTest extends TestCase
{
    private UpdateCartStatusRepository $updateCartStatusRepository;
    private CheckoutCartAction $checkoutCartAction;

    protected function setUp(): void
    {
        $this->updateCartStatusRepository = self::createMock(UpdateCartStatusRepository::class);
        $this->checkoutCartAction = new CheckoutCartAction($this->updateCartStatusRepository);
        parent::setUp();
    }

    public function testItThrowsEmptyCartExceptionWhenCartIsEmpty()
    {
        $cart = new Cart(UuidV4::v4(), [], CartStatus::New);

        self::expectException(EmptyCartException::class);

        ($this->checkoutCartAction)($cart);
    }

    public function testItThrowsCartAlreadyBoughtWhenCartIsAlreadyBought()
    {
        $cart = new Cart(UuidV4::v4(), ['not-empty'], CartStatus::Bought);

        self::expectException(CartAlreadyBoughtException::class);

        ($this->checkoutCartAction)($cart);
    }

    public function testItCheckoutsACart()
    {
        $cart = new Cart(UuidV4::v4(), [], CartStatus::New);
        $product = new Product(UuidV4::v4(), 'Name', 'Description', 42.78);
        $cartItem = new CartItem($cart, $product, 2);
        $cart->items = [$cartItem];

        $this->updateCartStatusRepository
            ->expects(self::once())
            ->method('update')
            ->with(self::callback(
                function (Cart $cart) {
                    return $cart->status == CartStatus::Bought;
                }
            ));

        $result = ($this->checkoutCartAction)($cart);

        self::assertEquals($cart, $result);
    }
}
