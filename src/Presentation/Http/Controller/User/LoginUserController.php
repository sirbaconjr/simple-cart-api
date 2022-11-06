<?php

namespace App\Presentation\Http\Controller\User;

use App\Application\LoginUserAction;
use App\Domain\Exception\UserPasswordMismatch;
use App\Domain\Exception\UserWithEmailNotFound;
use App\Presentation\Http\Controller\Controller;
use App\Presentation\Http\Exception\BadRequestException;
use App\Presentation\Http\Request\User\UserRequest;
use App\Presentation\Http\Response\User\TokenResponse;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class LoginUserController extends Controller
{
    public function __construct(
        private readonly LoginUserAction $loginUserAction
    )
    {
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $parsedRequest = new UserRequest($request);
            $token = ($this->loginUserAction)($parsedRequest->email, $parsedRequest->password);
        } catch (BadRequestException $exception) {
            return $this->buildResponseFromBadRequestException($exception, $response);
        } catch (UserPasswordMismatch|UserWithEmailNotFound) {
            return $this->buildResponseFromBadRequestException(
                new BadRequestException('user', "Unable to authenticate with given email and password"),
                $response
            );
        }

        return (new TokenResponse($token))->build($response);
    }
}
