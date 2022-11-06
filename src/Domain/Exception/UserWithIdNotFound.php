<?php

namespace App\Domain\Exception;

class UserWithIdNotFound extends \Exception
{
    public function __construct()
    {
        parent::__construct(
            message: "Unable to find a user with the specified id"
        );
    }
}
