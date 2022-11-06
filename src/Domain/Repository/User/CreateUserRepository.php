<?php

namespace App\Domain\Repository\User;

use App\Domain\Model\User;

interface CreateUserRepository
{
    /**
     * @param User $user
     * @return void
     */
    public function createUser(User $user): void;
}
