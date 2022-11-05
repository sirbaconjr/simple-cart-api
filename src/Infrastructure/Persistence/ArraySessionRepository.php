<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Enum\SessionKey;
use App\Domain\Repository\Session\SessionRepository;

class ArraySessionRepository implements SessionRepository
{
    private array $items = [];
    private bool $started = false;

    public function start(): void
    {
        $this->items = [];
        $this->started = true;
    }

    public function set(SessionKey $key, mixed $value): void
    {
        $this->startIfNeeded();
        $this->items[$key->value] = $value;
    }

    public function get(SessionKey $key, mixed $default = null): mixed
    {
        $this->startIfNeeded();
        return $this->items[$key->value] ?? $default;
    }

    private function startIfNeeded()
    {
        if (!$this->started) {
            $this->start();
        }
    }
}
