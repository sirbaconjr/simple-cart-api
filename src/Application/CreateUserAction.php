<?php

namespace App\Application;

use App\Domain\Exception\InvalidEmail;
use App\Domain\Exception\InvalidPassword;
use App\Domain\Model\User;
use App\Domain\Repository\User\CreateUserRepository;
use App\Domain\Validator\UserValidator;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Uid\UuidV4;

class CreateUserAction
{
    public function __construct(
        private readonly CreateUserRepository    $saveUserRepository,
        private readonly PasswordHasherInterface $hasher
    ) {}

    /**
     * @param string $email
     * @param string $plainPassword
     * @return User
     * @throws InvalidEmail
     * @throws InvalidPassword
     */
    public function __invoke(string $email, string $plainPassword): User
    {
        UserValidator::isEmailValid($email);
        UserValidator::isPasswordValid($plainPassword);

        $hashedPassword = $this->hasher->hash($plainPassword);

        $user = new User(
            id: UuidV4::v4(),
            email: $email,
            password: $hashedPassword
        );

        $this->saveUserRepository->createUser($user);

        return $user;
    }
}
