<?php

namespace App\Domain\Enum;

enum CartStatus: string
{
    case New = 'new';
    case Bought = 'bought';
}
