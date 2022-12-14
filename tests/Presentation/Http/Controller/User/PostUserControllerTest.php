<?php

namespace Tests\Presentation\Http\Controller\User;

use App\Domain\Enum\UserType;
use App\Domain\Model\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Uid\UuidV4;
use Tests\AppTestCase;

class PostUserControllerTest extends AppTestCase
{
    public function testItCreatesAUser(): void
    {
        $email = 'user@company.com';
        $password = '12345678';

        $request = $this->createJsonRequest('POST', '/api/user', [
            'email' => $email,
            'password' => $password,
            'type' => UserType::Customer
        ]);
        $response = $this->executeRequestAndParseResponse($request);

        $user = $this->getService(EntityManager::class)
            ->getRepository(User::class)
            ->find(UuidV4::fromString($response['data']['id']));

        self::assertEquals($email, $user->email);
        self::assertEquals(UserType::Customer, $user->type);
        self::assertNotEquals($password, $user->password);
        self::assertTrue(
            $this->getService(PasswordHasherInterface::class)->verify(
                $user->password,
                $password
            )
        );
    }

    /**
     * @dataProvider invalidEmailProvider
     *
     * @param string $email
     * @param string $message
     * @return void
     */
    public function testItValidatesEmail(string $email, string $message): void
    {
        $request = $this->createJsonRequest('POST', '/api/user', [
            'email' => $email,
            'password' => '12345678',
            'type' => UserType::Customer
        ]);
        $response = $this->app->handle($request);
        $body = json_decode((string) $response->getBody(), true);

        self::assertEquals(400, $response->getStatusCode());
        self::assertEquals(
            [
                'data' => [],
                'errors' => [
                    'email' => $message
                ]
            ],
            $body
        );
    }

    public function invalidEmailProvider(): array
    {
        return [
            [
                'not-a-valid-email',
                'The provided email is not a valid email address'
            ],
            [
                '',
                'The email field must be a non-empty string'
            ]
        ];
    }

    /**
     * @dataProvider invalidPasswordProvider
     *
     * @param string $password
     * @param string $message
     * @return void
     */
    public function testItValidatesPassword(string $password, string $message): void
    {
        $request = $this->createJsonRequest('POST', '/api/user', [
            'email' => 'user@example.com',
            'password' => $password,
            'type' => UserType::Customer
        ]);
        $response = $this->app->handle($request);
        $body = json_decode((string) $response->getBody(), true);

        self::assertEquals(400, $response->getStatusCode());
        self::assertEquals(
            [
                'data' => [],
                'errors' => [
                    'password' => $message
                ]
            ],
            $body
        );
    }

    public function invalidPasswordProvider(): array
    {
        return [
            [
                '1',
                'The password must have at least 8 characters'
            ],
            [
                '',
                'The password field must be a non-empty string'
            ]
        ];
    }

    /**
     * @dataProvider invalidUserTypeProvider
     *
     * @param string $password
     * @param string $message
     * @return void
     */
    public function testItValidatesUserType(string $type, string $message): void
    {
        $request = $this->createJsonRequest('POST', '/api/user', [
            'email' => 'user@example.com',
            'password' => '12345678',
            'type' => $type
        ]);
        $response = $this->app->handle($request);
        $body = json_decode((string) $response->getBody(), true);

        self::assertEquals(400, $response->getStatusCode());
        self::assertEquals(
            [
                'data' => [],
                'errors' => [
                    'type' => $message
                ]
            ],
            $body
        );
    }

    public function invalidUserTypeProvider(): array
    {
        return [
            [
                '',
                'The type field must be a valid user type'
            ],
            [
                'not-a-type',
                'The type field must be a valid user type'
            ]
        ];
    }
}
