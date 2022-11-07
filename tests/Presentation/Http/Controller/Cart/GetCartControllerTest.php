<?php

namespace Tests\Presentation\Http\Controller\Cart;

use App\Domain\Enum\CartStatus;
use App\Domain\Repository\Cart\GetCartRepository;
use Symfony\Component\Uid\UuidV4;
use Tests\AppTestCase;

class GetCartControllerTest extends AppTestCase
{
    public function testItGetsCartFromSession()
    {
        $request = $this->createJsonRequest('GET', '/api/cart');
        $firstResponse = json_decode((string) $this->app->handle($request)->getBody(), true);
        $secondResponse = json_decode((string) $this->app->handle($request)->getBody(), true);

        self::assertEquals(
            $firstResponse['data']['id'],
            $secondResponse['data']['id']
        );
    }

    public function testItCreatesAEmptyCartWithStatusNew()
    {
        $request = $this->createJsonRequest('GET', '/api/cart');
        $response = $this->executeRequestAndParseResponse($request);

        self::assertEquals(
            [],
            $response['data']['items']
        );

        self::assertEquals(
            CartStatus::New->value,
            $response['data']['status']
        );

        self::assertEquals(
            0,
            $response['data']['total']
        );

        $cart = $this->getService(GetCartRepository::class)
            ->getCart(UuidV4::fromString($response['data']['id']));

        self::assertEquals(
            CartStatus::New,
            $cart->status
        );

        self::assertEmpty($cart->items);
    }
}
