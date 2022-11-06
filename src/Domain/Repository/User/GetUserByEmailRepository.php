<?php

namespace App\Domain\Repository\User;

use App\Domain\Exception\UserWithEmailNotFound;
use App\Domain\Model\User;

interface GetUserByEmailRepository
{
    /**
     * @param string $email
     * @return User
     *
     * @throws UserWithEmailNotFound
     */
    public function findUserByEmail(string $email): User;
}
