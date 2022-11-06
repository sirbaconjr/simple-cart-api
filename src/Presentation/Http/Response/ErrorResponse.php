<?php

namespace App\Presentation\Http\Response;

class ErrorResponse extends Response
{
    public function __construct(
        string $key,
        string $message,
        private readonly int $statusCode
    )
    {
        $this->errors[$key] = $message;
    }

    protected function getData(): array
    {
        return [];
    }

    protected function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
