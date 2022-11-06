<?php

namespace App\Presentation\Http\Response\User;

use App\Domain\Model\User;
use App\Presentation\Http\Response\Response;

class UserResponse extends Response
{
    public function __construct(
        private readonly User $user
    )
    {
    }

    protected function getData(): array
    {
        return [
            'id' => $this->user->id,
            'email' => $this->user->email
        ];
    }

    protected function getStatusCode(): int
    {
        return 200;
    }
}
