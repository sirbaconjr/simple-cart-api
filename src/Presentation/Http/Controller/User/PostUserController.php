<?php

namespace App\Presentation\Http\Controller\User;

use App\Application\CreateUserAction;
use App\Domain\Exception\InvalidEmail;
use App\Domain\Exception\InvalidPassword;
use App\Presentation\Http\Controller\Controller;
use App\Presentation\Http\Exception\BadRequestException;
use App\Presentation\Http\Request\User\PostUserRequest;
use App\Presentation\Http\Response\User\UserResponse;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class PostUserController extends Controller
{
    public function __construct(
        private readonly CreateUserAction $createUserAction
    )
    {
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $parsedRequest = new PostUserRequest($request);

            $user = ($this->createUserAction)($parsedRequest->email, $parsedRequest->password, $parsedRequest->type);
        } catch (BadRequestException $exception) {
            return $this->buildResponseFromBadRequestException($exception, $response);
        } catch (InvalidEmail $exception) {
            return $this->buildResponseFromAnyException(
                'email',
                $exception,
                $response
            );
        } catch (InvalidPassword $exception) {
            return $this->buildResponseFromAnyException(
                'password',
                $exception,
                $response
            );
        }

        return (new UserResponse($user))->build($response);
    }
}
