<?php

namespace App\Domain\Exception;

class UserWithEmailNotFound extends \Exception
{
    public function __construct()
    {
        parent::__construct(
            message: "Unable to find a user with the specified email address"
        );
    }
}
