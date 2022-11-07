<?php

namespace App\Presentation\Http\Middleware;

use App\Domain\Enum\UserType;
use App\Domain\Exception\InvalidToken;
use App\Domain\Exception\UserWithIdNotFound;
use App\Domain\Repository\User\GetUserByIdRepository;
use App\Domain\Security\TokenHandler;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class UserOfTypeAuthenticatedMiddleware implements MiddlewareInterface
{
    /**
     * @param TokenHandler $tokenHandler
     * @param GetUserByIdRepository $getUserByIdRepository
     * @param UserType[] $types
     */
    public function __construct(
        private readonly TokenHandler $tokenHandler,
        private readonly GetUserByIdRepository $getUserByIdRepository,
        private readonly array $types
    ) {}

    public function process(RequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $authorization = $request->getHeader('Authorization');

        if (empty($authorization)) {
            return $this->unauthorized();
        }

        $value = $authorization[0];

        $regex = '/Bearer (.+)/m';

        $matches = [];
        preg_match($regex, $value, $matches);

        $token = $matches[1] ?? '';

        try {
            if (!$this->tokenHandler->validateToken($token)) {
                return $this->unauthorized();
            }

            try {
                $user = $this->getUserByIdRepository->getUserById(
                    $this->tokenHandler->getUserId($token)
                );
            } catch (\Exception) {
                return $this->unauthorized();
            }

            if (!in_array($user->type, $this->types)) {
                return $this->forbidden();
            }

            return $handler->handle(
                $request->withAttribute(
                    'user',
                    $user
                )
            );
        } catch (InvalidToken | UserWithIdNotFound) {
            return $this->unauthorized();
        }
    }

    private function unauthorized(): ResponseInterface
    {
        $response = new Response();

        return $response
            ->withStatus(401);
    }

    private function forbidden(): ResponseInterface
    {
        $response = new Response();

        return $response
            ->withStatus(403);
    }
}
