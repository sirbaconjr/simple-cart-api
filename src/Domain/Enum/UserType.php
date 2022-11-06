<?php

namespace App\Domain\Enum;

enum UserType: string
{
    case Customer = 'customer';
    case Manager = 'manager';
}
