<?php

namespace Tests\Presentation\Http\Controller\Cart;

use App\Application\GetCartAction;
use App\Domain\Enum\SessionKey;
use App\Domain\Model\Product;
use App\Domain\Repository\Product\CreateProductRepository;
use App\Domain\Repository\Session\SessionRepository;
use Selective\TestTrait\Traits\HttpJsonTestTrait;
use Selective\TestTrait\Traits\HttpTestTrait;
use Symfony\Component\Uid\UuidV4;
use Tests\AppTestCase;

class PostCartControllerTest extends AppTestCase
{
    use HttpTestTrait;
    use HttpJsonTestTrait;

    public function testItAddProductToCart()
    {
        $cart = $this->getService(GetCartAction::class)();

        $sessionRepository = $this->getService(SessionRepository::class);
        $sessionRepository->set(SessionKey::Cart, $cart->id);

        $product = new Product(UuidV4::v4(), 'name', 'description', 42.78);
        $createProductRepository = $this->getService(CreateProductRepository::class);
        $createProductRepository->createProduct($product);

        $payload = [
            'items' => [
                [
                    'id' => $product->id->jsonSerialize(),
                    'amount' => 1
                ]
            ]
        ];

        $request = $this->createJsonRequest('POST', '/api/cart', $payload);

        $response = $this->executeRequestAndParseResponse($request);

        self::assertEquals($payload['items'], $response['data']['items']);
        self::assertEquals([], $response['errors']);
    }

    /**
     * @dataProvider requestValidatorProvider
     *
     * @param array $payload
     * @param array $expected
     * @return void
     */
    public function testItValidatesRequest(array $payload, array $expected)
    {
        $request = $this->createJsonRequest('POST', '/api/cart', $payload);

        $response = $this->executeRequestAndParseResponse($request);

        self::assertEquals(
            $expected,
            $response
        );
    }

    public function requestValidatorProvider(): array
    {
        return [
            [
                [
                    'items' => [
                        [
                            'id' => 'not-an-uuid',
                            'amount' => 1
                        ]
                    ]
                ],
                [
                    'data' => [],
                    'errors' => [
                        'items.0.id' => "ID is not a valid UUID4 string"
                    ]
                ]
            ],
            [
                [
                    'items' => [
                        [
                            'id' => UuidV4::v4()->jsonSerialize(),
                            'amount' => 'not-a-number'
                        ]
                    ]
                ],
                [
                    'data' => [],
                    'errors' => [
                        'items.0.id' => "Amount is not a number"
                    ]
                ]
            ]
        ];
    }

    public function testItCreatesCartItemAndReturnsErrorsForInvalid()
    {
        $cart = $this->getService(GetCartAction::class)();

        $sessionRepository = $this->getService(SessionRepository::class);
        $sessionRepository->set(SessionKey::Cart, $cart->id);

        $product = new Product(UuidV4::v4(), 'name', 'description', 42.78);
        $createProductRepository = $this->getService(CreateProductRepository::class);
        $createProductRepository->createProduct($product);

        $anotherProduct = new Product(
            UuidV4::v4(),
            'another name',
            'another description',
            78.42
        );
        $createProductRepository->createProduct($anotherProduct);

        $randomId = UuidV4::v4();

        $payload = [
            'items' => [
                [
                    'id' => $product->id->jsonSerialize(),
                    'amount' => 1
                ],
                [
                    'id' => $anotherProduct->id->jsonSerialize(),
                    'amount' => 0
                ],
                [
                    'id' => $randomId->jsonSerialize(),
                    'amount' => 1
                ]
            ]
        ];

        $request = $this->createJsonRequest('POST', '/api/cart', $payload);

        $response = $this->executeRequestAndParseResponse($request);

        self::assertEquals(
            [
                [
                    'id' => $product->id->jsonSerialize(),
                    'amount' => 1
                ]
            ],
            $response['data']['items']
        );
        self::assertEquals(
            [
                'items.1' => 'Invalid amount, must be greater than 0',
                'items.2' => "Product with ID '{$randomId->jsonSerialize()}' not found"
            ],
            $response['errors']
        );
    }
}
