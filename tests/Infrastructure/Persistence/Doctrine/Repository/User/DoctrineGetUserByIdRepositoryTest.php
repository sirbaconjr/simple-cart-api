<?php

namespace Tests\Infrastructure\Persistence\Doctrine\Repository\User;

use App\Domain\Enum\UserType;
use App\Domain\Model\User;
use App\Infrastructure\Persistence\Doctrine\Repository\User\DoctrineCreateUserRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\User\DoctrineGetUserByIdRepository;
use Symfony\Component\Uid\UuidV4;
use Tests\AppTestCase;

class DoctrineGetUserByIdRepositoryTest extends AppTestCase
{
    public function testItGetsUser()
    {
        $createUserRepository = $this->getService(DoctrineCreateUserRepository::class);

        $user = new User(UuidV4::v4(), 'user@copmany.com', '12345678', UserType::Customer);
        $createUserRepository->createUser($user);

        $foundUser = $this->getService(DoctrineGetUserByIdRepository::class)
            ->getUserById($user->id);

        self::assertEquals($user, $foundUser);
    }
}
