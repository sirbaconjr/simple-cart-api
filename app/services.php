<?php

use App\Domain\Repository\Cart\CreateCartRepository;
use App\Domain\Repository\Cart\GetCartRepository;
use App\Domain\Repository\Cart\UpdateCartStatusRepository;
use App\Domain\Repository\CartItem\CreateCartItemRepository;
use App\Domain\Repository\Product\CreateProductRepository;
use App\Domain\Repository\Product\DeleteProductRepository;
use App\Domain\Repository\Product\GetAllProductsRepository;
use App\Domain\Repository\Product\UpdateProductRepository;
use App\Domain\Repository\Session\SessionRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\Cart\DoctrineCreateCartRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\Cart\DoctrineGetCartRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\Cart\DoctrineUpdateCartStatusRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\CartItem\DoctrineCreateCartItemRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\Product\DoctrineCreateProductRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\Product\DoctrineDeleteProductRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\Product\DoctrineGetAllProductsRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\Product\DoctrineUpdateProductRepository;
use App\Infrastructure\Persistence\Doctrine\Types\UuidType;
use App\Infrastructure\Persistence\PHPSessionRepository;
use DI\Container;
use DI\ContainerBuilder;
use Doctrine\DBAL\Logging\Middleware;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Factory\ServerRequestFactory;
use function DI\get;

return [
    //Logger
    LoggerInterface::class => function () {
        // create a log channel
        $log = new \Monolog\Logger('name');
        $log->pushHandler(new StreamHandler(__DIR__.'/../logs/slim.log', Level::Debug));

        return $log;
    },
    //Doctrine
    EntityManager::class => function (Container $container) {
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
        $config->setMiddlewares([new Middleware($container->get(LoggerInterface::class))]);

        if (!Type::hasType('uuid')) {
            Type::addType('uuid', UuidType::class);
        }

        return EntityManager::create($settings['connection'], $config);
    },
    CreateCartRepository::class => get(DoctrineCreateCartRepository::class),
    GetCartRepository::class => get(DoctrineGetCartRepository::class),
    UpdateCartStatusRepository::class => get(DoctrineUpdateCartStatusRepository::class),
    CreateCartItemRepository::class => get(DoctrineCreateCartItemRepository::class),
    CreateProductRepository::class => get(DoctrineCreateProductRepository::class),
    DeleteProductRepository::class => get(DoctrineDeleteProductRepository::class),
    GetAllProductsRepository::class => get(DoctrineGetAllProductsRepository::class),
    UpdateProductRepository::class => get(DoctrineUpdateProductRepository::class),
    SessionRepository::class => get(PHPSessionRepository::class),
    ServerRequestFactoryInterface::class => get(ServerRequestFactory::class)
];
