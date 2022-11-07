<?php

namespace Tests\Presentation\Http\Controller\User;

use App\Domain\Enum\UserType;
use App\Domain\Model\User;
use App\Domain\Repository\User\CreateUserRepository;
use App\Domain\Security\TokenHandler;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Uid\UuidV4;
use Tests\AppTestCase;

class LoginUserControllerTest extends AppTestCase
{
    public function testItLoginUser(): void
    {
        $plainPassword = '12345678';
        $hashedPassword = $this->getService(PasswordHasherInterface::class)->hash($plainPassword);
        $user = new User(UuidV4::v4(), 'user@company.com', $hashedPassword, UserType::Customer);
        $this->getService(CreateUserRepository::class)->createUser($user);

        $request = $this->createJsonRequest('POST', '/api/login', [
            'email' => $user->email,
            'password' => $plainPassword
        ]);
        $response = $this->executeRequestAndParseResponse($request);

        self::assertTrue($this->getService(TokenHandler::class)->validateToken($response['data']['token']));
    }

    public function testItReturnsExceptionWhenUserDoesNotExist(): void
    {
        $request = $this->createJsonRequest('POST', '/api/login', [
            'email' => 'invalid-email',
            'password' => 'invalid-password'
        ]);
        $response = $this->app->handle($request);
        $body = json_decode((string) $response->getBody(), true);

        self::assertEquals(400, $response->getStatusCode());
        self::assertEquals(
            [
                'data' => [],
                'errors' => [
                    'user' => 'Unable to authenticate with given email and password'
                ]
            ],
            $body
        );
    }

    public function testItReturnsExceptionWhenUserPasswordMismatch(): void
    {
        $user = new User(UuidV4::v4(), 'user@company.com', '12345678', UserType::Customer);
        $this->getService(CreateUserRepository::class)->createUser($user);

        $request = $this->createJsonRequest('POST', '/api/login', [
            'email' => $user->email,
            'password' => 'invalid-password'
        ]);
        $response = $this->app->handle($request);
        $body = json_decode((string) $response->getBody(), true);

        self::assertEquals(400, $response->getStatusCode());
        self::assertEquals(
            [
                'data' => [],
                'errors' => [
                    'user' => 'Unable to authenticate with given email and password'
                ]
            ],
            $body
        );
    }
}
