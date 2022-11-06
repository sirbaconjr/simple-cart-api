<?php

namespace App\Domain\Exception;

class InvalidToken extends \Exception
{
    public function __construct(\Throwable $previous = null)
    {
        parent::__construct(
            message: "The provided token is not valid",
            previous: $previous
        );
    }
}
