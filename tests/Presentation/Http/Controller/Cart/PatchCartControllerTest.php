<?php

namespace Tests\Presentation\Http\Controller\Cart;

use App\Domain\Enum\CartStatus;
use App\Domain\Repository\Cart\GetCartRepository;
use Selective\TestTrait\Traits\HttpJsonTestTrait;
use Selective\TestTrait\Traits\HttpTestTrait;
use Symfony\Component\Uid\UuidV4;
use Tests\AppTestCase;

class PatchCartControllerTest extends AppTestCase
{
    use HttpTestTrait;
    use HttpJsonTestTrait;

    public function testItUpdatesCartStatusToBought()
    {
        $request = $this->createJsonRequest('PATCH', '/api/cart', [
            'status' => CartStatus::Bought->value
        ]);

        $response = json_decode((string) $this->app->handle($request)->getBody(), true);

        self::assertEquals(
            CartStatus::Bought->value,
            $response['data']['status']
        );

        $cart = $this->getService(GetCartRepository::class)
            ->getCart(UuidV4::fromString($response['data']['id']));

        self::assertEquals(
            CartStatus::Bought,
            $cart->status
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
        $request = $this->createJsonRequest('PATCH', '/api/cart', [
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
