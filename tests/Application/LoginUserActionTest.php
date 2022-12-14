<?php

namespace Tests\Application;

use App\Application\LoginUserAction;
use App\Domain\Enum\UserType;
use App\Domain\Exception\UserPasswordMismatch;
use App\Domain\Exception\UserWithEmailNotFound;
use App\Domain\Model\User;
use App\Domain\Repository\User\GetUserByEmailRepository;
use App\Domain\Security\TokenHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Uid\UuidV4;

class LoginUserActionTest extends TestCase
{
    private PasswordHasherInterface $passwordHasher;
    private GetUserByEmailRepository $getUserByEmailRepository;
    private TokenHandler $tokenHandler;
    private LoginUserAction $loginUserAction;

    protected function setUp(): void
    {
        $this->passwordHasher = self::createMock(PasswordHasherInterface::class);
        $this->getUserByEmailRepository = self::createMock(GetUserByEmailRepository::class);
        $this->tokenHandler = self::createMock(TokenHandler::class);
        $this->loginUserAction = new LoginUserAction(
            $this->passwordHasher,
            $this->getUserByEmailRepository,
            $this->tokenHandler
        );
        parent::setUp(); // TODO: Change the autogenerated stub
    }

    public function testItLoginUserAndReturnsToken()
    {
        $user = new User(UuidV4::v4(), 'user@company.com', '12345678', UserType::Customer);
        $token = 'just-a-string';

        $this->getUserByEmailRepository
            ->expects($this->once())
            ->method('findUserByEmail')
            ->with($user->email)
            ->willReturn($user);

        $this->tokenHandler
            ->expects($this->once())
            ->method('issueToken')
            ->with($user)
            ->willReturn($token);

        $this->passwordHasher
            ->expects($this->once())
            ->method('verify')
            ->with($user->password, $user->password)
            ->willReturn(true);

        $response = ($this->loginUserAction)($user->email, $user->password);

        $this->assertEquals(
            $token,
            $response
        );
    }

    public function testItThrowsUserWithEmailNotFound()
    {
        $user = new User(UuidV4::v4(), 'user@company.com', '12345678', UserType::Customer);

        $this->getUserByEmailRepository
            ->expects($this->once())
            ->method('findUserByEmail')
            ->with($user->email)
            ->willThrowException(new UserWithEmailNotFound());

        self::expectException(UserWithEmailNotFound::class);

        ($this->loginUserAction)($user->email, $user->password);
    }

    public function testItThrowsUserPasswordMismatch()
    {
        $user = new User(UuidV4::v4(), 'user@company.com', '12345678', UserType::Customer);
        $notTheSamePassword = 'not-the-same-password';

        $this->getUserByEmailRepository
            ->expects($this->once())
            ->method('findUserByEmail')
            ->with($user->email)
            ->willReturn($user);

        $this->passwordHasher
            ->expects($this->once())
            ->method('verify')
            ->with($user->password, $notTheSamePassword)
            ->willReturn(false);

        self::expectException(UserPasswordMismatch::class);

        ($this->loginUserAction)($user->email, $notTheSamePassword);
    }
}
