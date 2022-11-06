<?php

namespace App\Presentation\Http\Request\User;

use App\Presentation\Http\Exception\BadRequestException;
use App\Presentation\Http\Request\Request;

class UserRequest extends Request
{
    public readonly string $email;
    public readonly string $password;

    /**
     * @param array $body
     * @return void
     * @throws BadRequestException
     */
    protected function setup(array $body): void
    {
        $this->email = $this->getStringFromBody($body, 'email');
        $this->password = $this->getStringFromBody($body, 'password');
    }

    /**
     * @param array $body
     * @param string $key
     * @return string
     * @throws BadRequestException
     */
    private function getStringFromBody(array $body, string $key): string
    {
        if (!array_key_exists($key, $body) || !is_string($body[$key]) || empty($body[$key])) {
            throw new BadRequestException($key, "The $key field must be a non-empty string");
        }

        return $body[$key];
    }
}
