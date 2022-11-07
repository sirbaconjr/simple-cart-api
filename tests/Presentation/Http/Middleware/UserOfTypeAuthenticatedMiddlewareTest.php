<?php

namespace Tests\Presentation\Http\Middleware;

use App\Domain\Enum\UserType;
use App\Domain\Model\User;
use App\Domain\Repository\User\CreateUserRepository;
use App\Domain\Repository\User\GetUserByIdRepository;
use App\Domain\Security\TokenHandler;
use App\Presentation\Http\Middleware\UserOfTypeAuthenticatedMiddleware;
use Psr\Http\Server\RequestHandlerInterface;
use Selective\TestTrait\Traits\HttpJsonTestTrait;
use Selective\TestTrait\Traits\HttpTestTrait;
use Slim\Psr7\Request;
use Symfony\Component\Uid\UuidV4;
use Tests\AppTestCase;

class UserOfTypeAuthenticatedMiddlewareTest extends AppTestCase
{
    use HttpTestTrait;
    use HttpJsonTestTrait;

    private UserOfTypeAuthenticatedMiddleware $middleware;

    public function setUpMiddleware(array $types)
    {
        $this->middleware = new UserOfTypeAuthenticatedMiddleware(
            $this->getService(TokenHandler::class),
            $this->getService(GetUserByIdRepository::class),
            $types
        );
    }

    public function testItThrowsUnauthorizedWhenAuthorizationIsEmpty()
    {
        $this->setUpMiddleware([]);

        $request = $this->createRequest('POST', '/api/users');

        $requestHandler = self::createMock(RequestHandlerInterface::class);
        $requestHandler
            ->expects($this->never())
            ->method('handle');

        $response = ($this->middleware)($request, $requestHandler);

        self::assertEquals(401, $response->getStatusCode());
    }

    public function testItThrowsUnauthorizedWhenTokenIsNotValid()
    {
        $this->setUpMiddleware([]);
        $token = 'not-a-token';

        $request = $this->createRequest('POST', '/api/users')
            ->withHeader('Authorization', 'Bearer ' . $token);

        $requestHandler = self::createMock(RequestHandlerInterface::class);
        $requestHandler
            ->expects($this->never())
            ->method('handle');

        $response = ($this->middleware)($request, $requestHandler);

        self::assertEquals(401, $response->getStatusCode());
    }

    public function testItThrowsUnauthorizedWhenUserDoesNotExist()
    {
        $user = new User(UuidV4::v4(), 'user@company.com', '12345678', UserType::Customer);
        $this->setUpMiddleware([UserType::Customer]);

        $token = $this->getService(TokenHandler::class)->issueToken($user);

        $request = $this->createRequest('POST', '/api/users')
            ->withHeader('Authorization', 'Bearer ' . $token);

        $requestHandler = self::createMock(RequestHandlerInterface::class);
        $requestHandler
            ->expects($this->never())
            ->method('handle');

        $response = ($this->middleware)($request, $requestHandler);

        self::assertEquals(401, $response->getStatusCode());
    }

    /**
     * @dataProvider invalidUserOfTypeProvider
     *
     * @param UserType $type
     * @param array $typesToMiddleware
     * @return void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function testItThrowsForbiddenWhenUserTypeDoesNotMatch(UserType $type, array $typesToMiddleware): void
    {
        $user = new User(UuidV4::v4(), 'user@company.com', '12345678', $type);
        $this->setUpMiddleware($typesToMiddleware);

        $this->getService(CreateUserRepository::class)->createUser($user);
        $token = $this->getService(TokenHandler::class)->issueToken($user);

        $request = $this->createRequest('POST', '/api/users')
            ->withHeader('Authorization', 'Bearer ' . $token);

        $requestHandler = self::createMock(RequestHandlerInterface::class);
        $requestHandler
            ->expects($this->never())
            ->method('handle');

        $response = ($this->middleware)($request, $requestHandler);

        self::assertEquals(403, $response->getStatusCode());
    }

    public function invalidUserOfTypeProvider(): array
    {
        return [
            [
                UserType::Customer,
                [UserType::Manager]
            ],
            [
                UserType::Customer,
                []
            ],
            [
                UserType::Manager,
                [UserType::Customer]
            ],
            [
                UserType::Manager,
                []
            ]
        ];
    }

    /**
     * @dataProvider validUserOfTypeProvider
     *
     * @param UserType $type
     * @param array $typesToMiddleware
     * @return void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function testItAuthenticatesUserOfType(UserType $type, array $typesToMiddleware): void
    {
        $user = new User(UuidV4::v4(), 'user@company.com', '12345678', $type);
        $this->setUpMiddleware($typesToMiddleware);

        $this->getService(CreateUserRepository::class)->createUser($user);
        $token = $this->getService(TokenHandler::class)->issueToken($user);

        $request = $this->createRequest('POST', '/api/users')
            ->withHeader('Authorization', 'Bearer ' . $token);

        $requestHandler = self::createMock(RequestHandlerInterface::class);
        $requestHandler
            ->expects($this->once())
            ->method('handle')
            ->with(self::callback(
                function (Request $request) use ($user) {
                    return $request->getAttribute('user') == $user;
                }
            ));

        ($this->middleware)($request, $requestHandler);
    }

    public function validUserOfTypeProvider(): array
    {
        return [
            [
                UserType::Customer,
                [UserType::Customer, UserType::Manager]
            ],
            [
                UserType::Customer,
                [UserType::Customer]
            ],
            [
                UserType::Manager,
                [UserType::Customer, UserType::Manager]
            ],
            [
                UserType::Manager,
                [UserType::Manager]
            ]
        ];
    }
}
