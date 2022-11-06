<?php

namespace App\Presentation\Http\Exception;

class BadRequestException extends \Exception
{
    public function __construct(public readonly string $key, string $message, int $code = 400)
    {
        parent::__construct(
            message: $message,
            code: $code
        );
    }
}
