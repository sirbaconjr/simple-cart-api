<?php

namespace Tests\Presentation\Http\Controller\Product;

use App\Domain\Enum\UserType;
use App\Domain\Model\Product;
use App\Domain\Repository\Product\CreateProductRepository;
use App\Domain\Repository\Product\GetProductRepository;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\HttpJsonTestTrait;
use Selective\TestTrait\Traits\HttpTestTrait;
use Symfony\Component\Uid\UuidV4;
use Tests\AppTestCase;

class PutProductControllerTest extends AppTestCase
{
    public function testItUpdatesAProduct(): void
    {
        $this->getUser(UserType::Manager);

        $product = new Product(UuidV4::v4(), 'name', 'description', 42.78);
        $this->getService(CreateProductRepository::class)->createProduct($product);
        $anotherName = 'another name';
        $anotherDescription = 'another description';
        $anotherPrice = 78.42;

        $request = $this->createJsonAuthenticatedRequest('PUT', "/api/products/{$product->id->jsonSerialize()}", [
            'name' => $anotherName,
            'description' => $anotherDescription,
            'price' => $anotherPrice
        ]);
        $response = $this->executeRequestAndParseResponse($request);

        $freshProduct = $this->getService(GetProductRepository::class)
            ->getProduct($product->id);

        self::assertEquals(['success' => true], $response['data']);
        self::assertEquals($anotherName, $freshProduct->name);
        self::assertEquals($anotherDescription, $freshProduct->description);
        self::assertEquals($anotherPrice, $freshProduct->price);
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

        $id = UuidV4::v4();
        $request = $this->createJsonAuthenticatedRequest('PUT', "/api/products/{$id->jsonSerialize()}", $payload);

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
