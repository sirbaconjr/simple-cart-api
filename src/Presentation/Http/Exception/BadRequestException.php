<?php

namespace App\Presentation\Http\Exception;

class BadRequestException extends \Exception
{
    public function __construct(public readonly string $key, string $message)
    {
        parent::__construct(
            message: $message,
            code: 404
        );
    }
}
