<?php

namespace Tests\Infrastructure\Persistence\Doctrine\Repository\User;

use App\Domain\Enum\UserType;
use App\Domain\Model\User;
use App\Infrastructure\Persistence\Doctrine\Repository\User\DoctrineCreateUserRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\User\DoctrineGetUserByEmailRepository;
use Symfony\Component\Uid\UuidV4;
use Tests\AppTestCase;

class DoctrineGetUserByEmailRepositoryTest extends AppTestCase
{
    public function testItCreatesACart()
    {
        $createUserRepository = $this->getService(DoctrineCreateUserRepository::class);

        $user = new User(UuidV4::v4(), 'user@copmany.com', '12345678', UserType::Customer);
        $createUserRepository->createUser($user);

        $foundUser = $this->getService(DoctrineGetUserByEmailRepository::class)
            ->findUserByEmail($user->email);

        self::assertEquals($user, $foundUser);
    }
}
