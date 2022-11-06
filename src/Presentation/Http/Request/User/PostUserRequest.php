<?php

namespace App\Presentation\Http\Request\User;

use App\Domain\Enum\UserType;
use App\Presentation\Http\Exception\BadRequestException;

class PostUserRequest extends UserRequest
{
    public readonly UserType $type;

    /**
     * @param array $body
     * @return void
     * @throws BadRequestException
     */
    protected function setup(array $body): void
    {
        parent::setup($body);

        $type = UserType::tryFrom($body['type'] ?? '');

        if (!$type) {
            throw new BadRequestException('type', 'The type field must be a valid user type');
        }

        $this->type = $type;
    }
}
