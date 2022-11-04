<?php

namespace App\Presentation\Http\Response;

class BooleanResponse extends Response
{
    public function __construct(
        private readonly bool $success
    )
    {
    }

    protected function getData(): array
    {
        return [
            'success' => $this->success
        ];
    }

    protected function getStatusCode(): int
    {
        return 200;
    }
}
