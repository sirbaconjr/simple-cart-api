<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository\User;

use App\Domain\Repository\User\CreateUserRepository;
use App\Domain\Model\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

class DoctrineCreateUserRepository implements CreateUserRepository
{
    public function __construct(
        private readonly EntityManager $entityManager
    ) {}

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function createUser(User $user): void
    {
        $this->entityManager->persist($user);

        $this->entityManager->flush();
    }
}
