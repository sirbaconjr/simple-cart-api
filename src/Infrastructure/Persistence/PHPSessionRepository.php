<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Enum\SessionKey;
use App\Domain\Repository\Session\SessionRepository;

class PHPSessionRepository implements SessionRepository
{
    private bool $started = false;

    public function start(): void
    {
        session_start();
        $this->started = true;
    }

    public function set(SessionKey $key, mixed $value): void
    {
        $this->startIfNeeded();
        $_SESSION[$key->value] = $value;
    }

    public function get(SessionKey $key, mixed $default = null): mixed
    {
        $this->startIfNeeded();
        return $_SESSION[$key->value] ?? $default;
    }

    private function startIfNeeded()
    {
        if (!$this->started) {
            $this->start();
        }
    }
}
