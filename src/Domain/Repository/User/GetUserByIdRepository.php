<?php

namespace App\Domain\Repository\User;

use App\Domain\Exception\UserWithIdNotFound;
use App\Domain\Model\User;

interface GetUserByIdRepository
{
    /**
     * @param string $id
     * @return User
     *
     * @throws UserWithIdNotFound
     */
    public function getUserById(string $id): User;
}
