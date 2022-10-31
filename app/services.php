<?php

use App\Infrastructure\Persistence\Doctrine\Types\UuidType;
use DI\ContainerBuilder;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        //Doctrine
        EntityManager::class => function () {
            $settings = [
                'dev_mode' => true,
                'metadata_dirs' => [__DIR__ . '/../src/Infrastructure/Persistence/Doctrine/Metadata'],
                'connection' => [
                    'driver' => 'pdo_mysql',
                    'host' => $_ENV['DB_HOST'],
                    'port' => $_ENV['DB_PORT'],
                    'dbname' => $_ENV['DB_DATABASE'],
                    'user' => $_ENV['DB_USERNAME'],
                    'password' => $_ENV['DB_PASSWORD'],
                    'charset' => 'utf8'
                ]
            ];

            $config = ORMSetup::createXMLMetadataConfiguration(
                $settings['metadata_dirs'],
                $settings['dev_mode']
            );

            if (!Type::hasType('uuid')) {
                Type::addType('uuid', UuidType::class);
            }

            return EntityManager::create($settings['connection'], $config);
        },
    ]);
};
