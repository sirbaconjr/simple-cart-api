<?php

namespace Tests\Application;

use App\Application\CheckoutCartAction;
use App\Domain\Enum\CartStatus;
use App\Domain\Enum\UserType;
use App\Domain\Exception\CartAlreadyBoughtException;
use App\Domain\Exception\EmptyCartException;
use App\Domain\Model\Cart;
use App\Domain\Model\CartItem;
use App\Domain\Model\Product;
use App\Domain\Model\User;
use App\Domain\Repository\Cart\ScheduleCartCheckoutEmailRepository;
use App\Domain\Repository\Cart\UpdateCartRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV4;

class CheckoutCartActionTest extends TestCase
{
    private UpdateCartRepository $updateCartStatusRepository;
    private ScheduleCartCheckoutEmailRepository $scheduleCartCheckoutEmailRepository;
    private CheckoutCartAction $checkoutCartAction;
    private User $user;

    protected function setUp(): void
    {
        $this->user = new User(UuidV4::v4(), 'user@company.com', '12345678', UserType::Customer);
        $this->updateCartStatusRepository = self::createMock(UpdateCartRepository::class);
        $this->scheduleCartCheckoutEmailRepository = self::createMock(
            ScheduleCartCheckoutEmailRepository::class
        );
        $this->checkoutCartAction = new CheckoutCartAction(
            $this->updateCartStatusRepository,
            $this->scheduleCartCheckoutEmailRepository
        );
        parent::setUp();
    }

    public function testItThrowsEmptyCartExceptionWhenCartIsEmpty()
    {
        $cart = new Cart(UuidV4::v4(), [], CartStatus::New);

        self::expectException(EmptyCartException::class);

        ($this->checkoutCartAction)($cart, $this->user);
    }

    public function testItThrowsCartAlreadyBoughtWhenCartIsAlreadyBought()
    {
        $cart = new Cart(UuidV4::v4(), ['not-empty'], CartStatus::Bought);

        self::expectException(CartAlreadyBoughtException::class);

        ($this->checkoutCartAction)($cart, $this->user);
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

        $this->scheduleCartCheckoutEmailRepository
            ->expects(self::once())
            ->method('schedule')
            ->with($cart);

        $result = ($this->checkoutCartAction)($cart, $this->user);

        self::assertEquals($cart, $result);
        self::assertEquals($cart->user, $this->user);
    }
}
