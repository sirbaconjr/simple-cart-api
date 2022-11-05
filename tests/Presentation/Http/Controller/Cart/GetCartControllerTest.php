<?php

namespace Tests\Presentation\Http\Controller\Cart;

use App\Domain\Enum\CartStatus;
use Selective\TestTrait\Traits\HttpJsonTestTrait;
use Selective\TestTrait\Traits\HttpTestTrait;
use Tests\AppTestCase;

class GetCartControllerTest extends AppTestCase
{
    use HttpTestTrait;
    use HttpJsonTestTrait;

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

    public function testItCreatesAEmptyCart()
    {
        $request = $this->createJsonRequest('GET', '/api/cart');
        $firstResponse = json_decode((string) $this->app->handle($request)->getBody(), true);

        self::assertEquals(
            $firstResponse['data']['items'],
            []
        );
    }

    public function testItCreatesACartWithStatusNew()
    {
        $request = $this->createJsonRequest('GET', '/api/cart');
        $firstResponse = json_decode((string) $this->app->handle($request)->getBody(), true);

        self::assertEquals(
            $firstResponse['data']['status'],
            CartStatus::New->value
        );
    }
}
