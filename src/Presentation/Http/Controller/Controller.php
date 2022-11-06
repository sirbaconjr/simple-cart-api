<?php

namespace App\Presentation\Http\Controller;

use App\Presentation\Http\Exception\BadRequestException;
use App\Presentation\Http\Response\ErrorResponse;
use Psr\Http\Message\ResponseInterface;

abstract class Controller
{
    protected function buildResponseFromBadRequestException(
        BadRequestException $exception,
        ResponseInterface $response
    ): ResponseInterface
    {
        return (new ErrorResponse(
            $exception->key,
            $exception->getMessage(),
            $exception->getCode()
        ))->build($response);
    }
}
