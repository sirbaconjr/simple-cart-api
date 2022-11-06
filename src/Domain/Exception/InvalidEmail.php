<?php

namespace App\Domain\Exception;

class InvalidEmail extends \Exception
{
    public function __construct()
    {
        parent::__construct(
            message: "The provided email is not a valid email address"
        );
    }
}
