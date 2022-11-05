<?php

use App\Domain\Repository\Session\SessionRepository;
use App\Infrastructure\Persistence\ArraySessionRepository;
use function DI\get;

return [
    SessionRepository::class => get(ArraySessionRepository::class)
];
