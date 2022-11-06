<?php

namespace Tests\Presentation\Http\Controller\Cart;

use App\Domain\Enum\CartStatus;
use Selective\TestTrait\Traits\HttpJsonTestTrait;
use Selective\TestTrait\Traits\HttpTestTrait;
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
