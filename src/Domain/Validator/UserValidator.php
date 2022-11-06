<?php

namespace App\Domain\Validator;

use App\Domain\Exception\InvalidEmail;
use App\Domain\Exception\InvalidPassword;

class UserValidator
{
    /**
     * @param string $email
     * @return void
     * @throws InvalidEmail
     */
    public static function isEmailValid(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmail();
        }
    }

    /**
     * @param string $password
     * @return void
     * @throws InvalidPassword
     */
    public static function isPasswordValid(string $password): void
    {
        if (strlen($password) < 8) {
            throw new InvalidPassword();
        }
    }
}
