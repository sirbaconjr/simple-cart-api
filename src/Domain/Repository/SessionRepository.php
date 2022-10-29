<?php

namespace App\Domain\Repository;

use App\Domain\Helper\SessionKey;

interface SessionRepository
{
    public function set(SessionKey $key, mixed $value): void;

    public function get(SessionKey $key, mixed $default): mixed;
}
