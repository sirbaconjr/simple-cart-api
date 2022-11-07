<?php

namespace Tests\Infrastructure\Persistence\Doctrine\Repository\User;

use App\Domain\Enum\UserType;
use App\Domain\Model\User;
use App\Infrastructure\Persistence\Doctrine\Repository\User\DoctrineCreateUserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Uid\UuidV4;
use Tests\AppTestCase;

class DoctrineCreateUserRepositoryTest extends AppTestCase
{
    public function testItCreatesAUser()
    {
        $createUserRepository = $this->getService(DoctrineCreateUserRepository::class);

        $user = new User(UuidV4::v4(), 'user@copmany.com', '12345678', UserType::Customer);
        $createUserRepository->createUser($user);

        $foundUser = $this->getService(EntityManager::class)
            ->getRepository(User::class)
            ->find($user->id);

        self::assertEquals($user, $foundUser);
    }
}
