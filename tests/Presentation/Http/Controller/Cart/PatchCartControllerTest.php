<?php

namespace Tests\Presentation\Http\Controller\Cart;

use App\Application\AddProductToCartAction;
use App\Application\GetCartAction;
use App\Domain\Enum\CartStatus;
use App\Domain\Model\Product;
use App\Domain\Repository\Cart\GetCartRepository;
use App\Domain\Repository\Cart\UpdateCartStatusRepository;
use App\Domain\Repository\Product\CreateProductRepository;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Uid\UuidV4;
use Tests\AppTestCase;

class PatchCartControllerTest extends AppTestCase
{
    public function testItUpdatesCartStatusToBought()
    {
        $product = new Product(UuidV4::v4(), 'name', 'description', 42.78);
        $this->getService(CreateProductRepository::class)->createProduct($product);

        $cart = $this->getService(GetCartAction::class)();

        ($this->getService(AddProductToCartAction::class))($cart, $product->id, 1);

        $this->AMQPChannel
            ->expects(self::once())
            ->method('basic_publish')
            ->with(
                self::callback(function (AMQPMessage $msg) use ($cart) {
                    return $msg->getBody() == $cart->id;
                })
            );

        $request = $this->createJsonAuthenticatedRequest('PATCH', '/api/cart', [
            'status' => CartStatus::Bought->value
        ]);

        $response = json_decode((string) $this->app->handle($request)->getBody(), true);

        self::assertEquals(
            CartStatus::Bought->value,
            $response['data']['status']
        );

        self::assertEquals(
            42.78,
            $response['data']['total']
        );

        $cart = $this->getService(GetCartRepository::class)
            ->getCart(UuidV4::fromString($response['data']['id']));

        self::assertEquals(
            CartStatus::Bought,
            $cart->status
        );
    }

    public function testItValidatesCantCheckoutEmptyCart()
    {
        $cart = $this->getService(GetCartAction::class)();
        $request = $this->createJsonAuthenticatedRequest('PATCH', '/api/cart', [
            'status' => CartStatus::Bought->value
        ]);

        $response = $this->app->handle($request);
        $body = json_decode((string) $response->getBody(), true);

        self::assertEquals(
            400,
            $response->getStatusCode()
        );

        self::assertEquals(
            [],
            $body['data']
        );

        self::assertEquals(
            [
                'id' => "Cart $cart->id is empty"
            ],
            $body['errors']
        );
    }

    public function testItValidatesCantCheckoutAlreadyBoughtCart()
    {
        $product = new Product(UuidV4::v4(), 'name', 'description', 42.78);
        $this->getService(CreateProductRepository::class)->createProduct($product);

        $cart = $this->getService(GetCartAction::class)();

        ($this->getService(AddProductToCartAction::class))($cart, $product->id, 1);

        $freshCart = $this->getService(GetCartRepository::class)->getCart($cart->id);
        $freshCart->status = CartStatus::Bought;
        $this->getService(UpdateCartStatusRepository::class)->update($freshCart);

        $request = $this->createJsonAuthenticatedRequest('PATCH', '/api/cart', [
            'status' => CartStatus::Bought->value
        ]);

        $response = $this->app->handle($request);
        $body = json_decode((string) $response->getBody(), true);

        self::assertEquals(
            400,
            $response->getStatusCode()
        );

        self::assertEquals(
            [],
            $body['data']
        );

        self::assertEquals(
            [
                'id' => "Cart $cart->id already bought"
            ],
            $body['errors']
        );
    }

    /**
     * @dataProvider invalidStatusProvider
     *
     * @param string $status
     * @return void
     */
    public function testItThrowsExceptionWhenTryingToPatchWithInvalidStatus(string $status): void
    {
        $request = $this->createJsonAuthenticatedRequest('PATCH', '/api/cart', [
            'status' => $status
        ]);

        $response = json_decode((string) $this->app->handle($request)->getBody(), true);

        self::assertEquals(
            [
                'status' => 'Invalid status'
            ],
            $response['errors']
        );
    }

    public function invalidStatusProvider(): array
    {
        return [
            [''],
            [CartStatus::New->value]
        ];
    }
}
