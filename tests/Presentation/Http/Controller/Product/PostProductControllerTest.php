<?php

namespace Tests\Presentation\Http\Controller\Product;

use App\Domain\Enum\UserType;
use App\Domain\Model\Product;
use App\Domain\Repository\Product\GetProductRepository;
use Selective\TestTrait\Traits\HttpJsonTestTrait;
use Selective\TestTrait\Traits\HttpTestTrait;
use Symfony\Component\Uid\UuidV4;
use Tests\AppTestCase;

class PostProductControllerTest extends AppTestCase
{
    public function testItCreatesAProduct(): void
    {
        $this->getUser(UserType::Manager);

        $name = 'name';
        $description = 'description';
        $price = 42.78;

        $request = $this->createJsonAuthenticatedRequest('POST', '/api/products', [
            'name' => $name,
            'description' => $description,
            'price' => $price
        ]);
        $response = $this->executeRequestAndParseResponse($request);

        $product = $this->getService(GetProductRepository::class)
            ->getProduct(UuidV4::fromString($response['data']['id']));

        self::assertEquals(
            [
                'id' => $product->id->jsonSerialize(),
                'name' => $name,
                'description' => $description,
                'price' => $price
            ],
            $response['data']
        );
    }

    /**
     * @dataProvider requestValidatorProvider
     *
     * @param array $payload
     * @param array $expected
     * @return void
     */
    public function testItValidatesRequest(array $payload, array $expected): void
    {
        $this->getUser(UserType::Manager);

        $request = $this->createJsonAuthenticatedRequest('POST', '/api/products', $payload);

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
                    'name' => '',
                ],
                [
                    'data' => [],
                    'errors' => [
                        'name' => "The name field must be a non-empty string"
                    ]
                ]
            ],
            [
                [
                    'name' => 123,
                ],
                [
                    'data' => [],
                    'errors' => [
                        'name' => "The name field must be a non-empty string"
                    ]
                ]
            ],
            [
                [
                    'name' => 'name',
                    'description' => '',
                ],
                [
                    'data' => [],
                    'errors' => [
                        'description' => "The description field must be a non-empty string"
                    ]
                ]
            ],
            [
                [
                    'name' => 'name',
                    'description' => 123,
                ],
                [
                    'data' => [],
                    'errors' => [
                        'description' => "The description field must be a non-empty string"
                    ]
                ]

            ],
            [
                [
                    'name' => 'name',
                    'description' => 'description',
                ],
                [
                    'data' => [],
                    'errors' => [
                        'price' => "The price field must be a number"
                    ]
                ]
            ],
            [
                [
                    'name' => 'name',
                    'description' => 'description',
                    'price' => 'not-a-number'
                ],
                [
                    'data' => [],
                    'errors' => [
                        'price' => "The price field must be a number"
                    ]
                ]
            ]
        ];
    }
}
