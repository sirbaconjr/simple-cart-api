<?php

namespace App\Domain\Security;

use App\Domain\Exception\InvalidToken;
use App\Domain\Model\User;

interface TokenHandler
{
    public function issueToken(User $user): string;

    /**
     * @param string $token
     * @return bool
     * @throws InvalidToken
     */
    public function validateToken(string $token): bool;

    /**
     * @param string $token
     * @return string
     * @throws InvalidToken
     */
    public function getUserId(string $token): string;
}
