<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository\User;

use App\Domain\Exception\UserWithEmailNotFound;
use App\Domain\Model\User;
use App\Domain\Repository\User\GetUserByEmailRepository;
use Doctrine\ORM\EntityManager;

class DoctrineGetUserByEmailRepository implements GetUserByEmailRepository
{
    public function __construct(
        private readonly EntityManager $entityManager
    ) {}

    public function findUserByEmail(string $email): User
    {
        $user = $this->entityManager->getRepository(User::class)
            ->findOneBy(['email' => $email]);

        if (!$user) {
            throw new UserWithEmailNotFound();
        }

        return $user;
    }
}
