<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Helper\SessionKey;
use App\Domain\Repository\SessionRepository;

class PHPSessionRepository implements SessionRepository
{
    public function set(SessionKey $key, mixed $value): void
    {
        session_start();
        $_SESSION[$key->value] = $value;
    }

    public function get(SessionKey $key, mixed $default = null): mixed
    {
        session_start();
        return $_SESSION[$key->value] ?? $default;
    }
}
