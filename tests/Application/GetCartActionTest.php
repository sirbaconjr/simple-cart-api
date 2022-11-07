<?php

namespace Tests\Application;

use App\Application\GetCartAction;
use App\Domain\Enum\CartStatus;
use App\Domain\Enum\SessionKey;
use App\Domain\Model\Cart;
use App\Domain\Repository\Cart\CreateCartRepository;
use App\Domain\Repository\Cart\GetCartRepository;
use App\Domain\Repository\Session\SessionRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV4;

class GetCartActionTest extends TestCase
{
    private GetCartRepository $getCartRepository;
    private CreateCartRepository $createCartRepository;
    private SessionRepository $sessionRepository;
    private GetCartAction $getCartAction;

    protected function setUp(): void
    {
        parent::setUp();
        $this->getCartRepository = self::createMock(GetCartRepository::class);
        $this->createCartRepository = self::createMock(CreateCartRepository::class);
        $this->sessionRepository = self::createMock(SessionRepository::class);
        $this->getCartAction = new GetCartAction(
            $this->getCartRepository,
            $this->createCartRepository,
            $this->sessionRepository
        );
    }

    public function testItGetsCartFromSession()
    {
        $id = UuidV4::v4();
        $cart = new Cart($id, [], CartStatus::New);

        $this->sessionRepository
            ->expects(self::once())
            ->method('get')
            ->with(SessionKey::Cart)
            ->willReturn($id->jsonSerialize());

        $this->getCartRepository
            ->expects(self::once())
            ->method('getCart')
            ->with($id)
            ->willReturn($cart);

        $result = ($this->getCartAction)();

        self::assertEquals($cart, $result);
    }

    public function testItCreatesNewCart()
    {
        $id = UuidV4::v4();

        $this->sessionRepository
            ->expects(self::once())
            ->method('get')
            ->with(SessionKey::Cart)
            ->willReturn(null);

        $this->getCartRepository
            ->expects(self::never())
            ->method('getCart');

        $this->createCartRepository
            ->expects($this->once())
            ->method('createCart')
            ->with(self::callback(
                function (Cart $cart) {
                    return $cart->status == CartStatus::New && empty($cart->items);
                }
            ));

        $result = ($this->getCartAction)();

        self::assertInstanceOf(Cart::class, $result);
    }
}
