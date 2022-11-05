<?php

namespace Tests;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\ContainerTestTrait;
use Slim\App;

class AppTestCase extends TestCase
{
    use ContainerTestTrait;

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
}
