<?php

namespace Tests;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request as SlimRequest;
use Slim\Psr7\Uri;

class AppTestCase extends TestCase
{
    protected App $app;

    protected function setUp(): void
    {
        $this->app = $this->getAppInstance();

        $em = $this->container(EntityManager::class);
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
    protected function container(string $service): mixed
    {
        return $this->app->getContainer()->get($service);
    }
}
