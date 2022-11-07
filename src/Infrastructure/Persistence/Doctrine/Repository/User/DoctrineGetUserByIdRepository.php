<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository\User;

use App\Domain\Exception\UserWithIdNotFound;
use App\Domain\Repository\User\GetUserByIdRepository;
use App\Domain\Model\User;
use Doctrine\ORM\EntityManager;

class DoctrineGetUserByIdRepository implements GetUserByIdRepository
{
    public function __construct(
        private readonly EntityManager $entityManager
    ) {}

    /**
     * @param string $id
     * @return User
     * @throws UserWithIdNotFound
     */
    public function getUserById(string $id): User
    {
        $user = $this->entityManager->getRepository(User::class)
            ->findOneBy(['id' => $id]);

        if (!$user) {
            throw new UserWithIdNotFound();
        }

        return $user;
    }
}
