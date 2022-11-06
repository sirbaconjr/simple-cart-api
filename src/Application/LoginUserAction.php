<?php

namespace App\Application;

use App\Domain\Exception\UserPasswordMismatch;
use App\Domain\Exception\UserWithEmailNotFound;
use App\Domain\Repository\User\GetUserByEmailRepository;
use App\Domain\Security\TokenHandler;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class LoginUserAction
{
    public function __construct(
        private readonly PasswordHasherInterface  $passwordHasher,
        private readonly GetUserByEmailRepository $repository,
        private readonly TokenHandler             $tokenHandler
    ) {}

    /**
     * @param string $email
     * @param string $plainPassword
     * @return string
     * @throws UserPasswordMismatch
     * @throws UserWithEmailNotFound
     */
    public function __invoke(string $email, string $plainPassword): string
    {
        $user = $this->repository->findUserByEmail($email);

        $isPasswordValid = $this->passwordHasher->verify($user->password, $plainPassword);

        if (!$isPasswordValid) {
            throw new UserPasswordMismatch();
        }

        return $this->tokenHandler->issueToken($user);
    }
}
