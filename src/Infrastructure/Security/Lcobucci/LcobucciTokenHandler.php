<?php

namespace App\Infrastructure\Security\Lcobucci;

use App\Domain\Exception\InvalidToken;
use App\Domain\Model\User;
use App\Domain\Security\TokenHandler;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Validation\Constraint\SignedWith;

class LcobucciTokenHandler implements TokenHandler
{
    public function __construct(
        private readonly Configuration $configuration
    ) {}

    public function issueToken(User $user): string
    {
        $now   = new \DateTimeImmutable();
        $token = $this->configuration
            ->builder()
            ->issuedBy($_ENV['APP_URL'])
            // Configures the audience (aud claim)
            ->relatedTo($user->id)
            // Configures the time that the token was issue (iat claim)
            ->issuedAt($now)
            // Configures the expiration time of the token (exp claim)
            ->expiresAt($now->modify('+1 hour'))
            // Builds a new token
            ->getToken($this->configuration->signer(), $this->configuration->signingKey());

        return $token->toString();
    }

    /**
     * @param string $token
     * @return bool
     * @throws InvalidToken
     */
    public function validateToken(string $token): bool
    {
        try {
            return $this->configuration
                ->validator()
                ->validate(
                    $this->configuration->parser()
                        ->parse($token),
                    new SignedWith($this->configuration->signer(), $this->configuration->signingKey())
                );
        } catch (\Throwable $throwable) {
            throw new InvalidToken($throwable);
        }
    }

    public function getUserId(string $token): string
    {
        try {
            return $this->configuration->parser()
                ->parse($token)
                ->claims()
                ->get('sub');
        } catch (\Throwable $throwable) {
            throw new InvalidToken($throwable);
        }
    }
}
