<?php

namespace Tests;

use App\Application\CreateUserAction;
use App\Domain\Enum\UserType;
use App\Domain\Model\User;
use App\Domain\Security\TokenHandler;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Selective\TestTrait\Traits\ContainerTestTrait;
use Selective\TestTrait\Traits\HttpJsonTestTrait;
use Selective\TestTrait\Traits\HttpTestTrait;
use Slim\App;
use Symfony\Component\Uid\UuidV4;

class AppTestCase extends TestCase
{
    use ContainerTestTrait;
    use HttpTestTrait;
    use HttpJsonTestTrait;

    protected ?User $user = null;

    protected App $app;

    protected function setUp(): void
    {
        $this->app = $this->getAppInstance();
        $this->setUpContainer($this->app->getContainer());
        $em = $this->getService(EntityManager::class);
        $schemaTool = new SchemaTool($em);
        $metadatas = $em->getMetadataFactory()->getAllMetadata();
        $schemaTool->updateSchema($metadatas, true);
    }

    /**
     * @template T
     * @param class-string<T> $service
     * @return T
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function getService(string $service): mixed
    {
        return $this->app->getContainer()->get($service);
    }

    /**
     * @return App
     */
    protected function getAppInstance(): App
    {
        return require __DIR__ . '/../app/app.php';
    }

    protected function executeRequestAndParseResponse(RequestInterface $request): array
    {
        return json_decode((string) $this->app->handle($request)->getBody(), true);
    }

    protected function getUser(UserType $type = UserType::Customer)
    {
        if (!$this->user) {
            $this->user = $this->getService(CreateUserAction::class)(
                'user@company.com',
                '12345678',
                $type);
        }

        return $this->user;
    }

    protected function createJsonAuthenticatedRequest(string $method, $uri, array $data = null): ServerRequestInterface
    {
        $token = $this->getService(TokenHandler::class)->issueToken($this->getUser());

        return $this->createJsonRequest($method, $uri, $data)
            ->withHeader('Authorization', 'Bearer ' . $token);
    }
}
