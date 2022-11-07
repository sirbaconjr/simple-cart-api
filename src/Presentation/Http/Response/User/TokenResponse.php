<?php

namespace App\Presentation\Http\Response\User;

use App\Presentation\Http\Response\Response;

class TokenResponse extends Response
{
    public function __construct(
        private readonly string $token
    )
    {
    }

    protected function getData(): array
    {
        return [
            'token' => $this->token
        ];
    }

    protected function getStatusCode(): int
    {
        return 200;
    }
}
