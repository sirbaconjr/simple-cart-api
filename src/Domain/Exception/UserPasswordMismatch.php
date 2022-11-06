<?php

namespace App\Domain\Exception;

class UserPasswordMismatch extends \Exception
{
    public function __construct()
    {
        parent::__construct("Unable to find a user with the given email and password");
    }
}
