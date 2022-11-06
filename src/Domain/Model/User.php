<?php

namespace App\Domain\Model;

use App\Domain\Enum\UserType;
use Symfony\Component\Uid\UuidV4;

class User
{
    public function __construct(
        public readonly UuidV4 $id,
        public readonly string $email,
        public string $password,
        public UserType $type
    ) {}
}
