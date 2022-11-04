<?php

use App\Infrastructure\Persistence\Doctrine\Types\UuidType;
use DI\ContainerBuilder;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Psr\Log\LoggerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        //Logger
        LoggerInterface::class => function () {
            // create a log channel
            $log = new \Monolog\Logger('name');
            $log->pushHandler(new StreamHandler(__DIR__.'/../logs/slim.log', Level::Debug));

            return $log;
        },
        //Doctrine
        EntityManager::class => function (\DI\Container $container) {
            $settings = [
                'dev_mode' => true,
                'metadata_dirs' => [__DIR__ . '/../src/Infrastructure/Persistence/Doctrine/Metadata'],
                'connection' => [
                    'url' => $_ENV['DB_URL']
                ]
            ];

            $config = ORMSetup::createXMLMetadataConfiguration(
                $settings['metadata_dirs'],
                $settings['dev_mode']
            );
            $config->setMiddlewares([new \Doctrine\DBAL\Logging\Middleware($container->get(LoggerInterface::class))]);

            if (!Type::hasType('uuid')) {
                Type::addType('uuid', UuidType::class);
            }

            return EntityManager::create($settings['connection'], $config);
        },
    ]);
};
