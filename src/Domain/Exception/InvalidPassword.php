<?php

namespace App\Domain\Exception;

class InvalidPassword extends \Exception
{
    public function __construct()
    {
        parent::__construct(
            message: 'The password must have at least 8 characters'
        );
    }
}
