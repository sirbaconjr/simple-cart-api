<?php

namespace Tests\Application;

use App\Application\CreateUserAction;
use App\Domain\Exception\InvalidEmail;
use App\Domain\Exception\InvalidPassword;
use App\Domain\Model\User;
use App\Domain\Repository\User\CreateUserRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class CreateUserActionTest extends TestCase
{
    private CreateUserRepository $createUserRepository;
    private PasswordHasherInterface $passwordHasher;
    private CreateUserAction $createUserAction;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createUserRepository = self::createMock(CreateUserRepository::class);
        $this->passwordHasher = self::createMock(PasswordHasherInterface::class);
        $this->createUserAction = new CreateUserAction($this->createUserRepository, $this->passwordHasher);
    }

    public function testItCreatesAUser(): void
    {
        $email = 'user@company.com';
        $password = '12345678';

        $this->createUserRepository
            ->expects($this->once())
            ->method('createUser')
            ->with(
                self::callback(
                    fn(User $user) =>
                        $user->email == $email && $user->password == $password
                )
            );

        $this->passwordHasher
            ->expects($this->once())
            ->method('hash')
            ->with($password)
            ->willReturn($password);

        $user = ($this->createUserAction)($email, $password);

        self::assertEquals(
            $user->email,
            $email
        );

        self::assertEquals(
            $user->password,
            $password
        );
    }

    public function testItThrowsInvalidEmail()
    {
        self::expectException(InvalidEmail::class);

        ($this->createUserAction)('not-an-email', '12345678');
    }

    public function testItThrowsInvalidPassword()
    {
        self::expectException(InvalidPassword::class);

        ($this->createUserAction)('user@company.com', '');
    }
}
