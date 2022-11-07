<?php

use App\Domain\Repository\Cart\CreateCartRepository;
use App\Domain\Repository\Cart\GetCartRepository;
use App\Domain\Repository\Cart\UpdateCartStatusRepository;
use App\Domain\Repository\CartItem\CreateCartItemRepository;
use App\Domain\Repository\Product\CreateProductRepository;
use App\Domain\Repository\Product\DeleteProductRepository;
use App\Domain\Repository\Product\GetAllProductsRepository;
use App\Domain\Repository\Product\GetProductRepository;
use App\Domain\Repository\Product\UpdateProductRepository;
use App\Domain\Repository\Session\SessionRepository;
use App\Domain\Model\User;
use App\Domain\Repository\User\CreateUserRepository;
use App\Domain\Repository\User\GetUserByEmailRepository;
use App\Domain\Repository\User\GetUserByIdRepository;
use App\Domain\Security\TokenHandler;
use App\Infrastructure\Persistence\Doctrine\Repository\Cart\DoctrineCreateCartRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\Cart\DoctrineGetCartRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\Cart\DoctrineUpdateCartStatusRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\CartItem\DoctrineCreateCartItemRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\Product\DoctrineCreateProductRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\Product\DoctrineDeleteProductRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\Product\DoctrineGetAllProductsRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\Product\DoctrineGetProductRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\Product\DoctrineUpdateProductRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\User\DoctrineCreateUserRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\User\DoctrineGetUserByEmailRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\User\DoctrineGetUserByIdRepository;
use App\Infrastructure\Persistence\Doctrine\Types\UuidType;
use App\Infrastructure\Persistence\PHPSessionRepository;
use App\Infrastructure\Security\Lcobucci\LcobucciTokenHandler;
use DI\Container;
use Doctrine\DBAL\Logging\Middleware;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Factory\ServerRequestFactory;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use function DI\get;

return [
    //Logger
    LoggerInterface::class => function () {
        // create a log channel
        $log = new \Monolog\Logger('name');
        $log->pushHandler(new StreamHandler(__DIR__.'/../logs/slim.log', Level::Debug));

        return $log;
    },
    //EntityManager
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
    GetProductRepository::class => get(DoctrineGetProductRepository::class),
    GetAllProductsRepository::class => get(DoctrineGetAllProductsRepository::class),
    UpdateProductRepository::class => get(DoctrineUpdateProductRepository::class),
    GetUserByEmailRepository::class => get(DoctrineGetUserByEmailRepository::class),
    GetUserByIdRepository::class => get(DoctrineGetUserByIdRepository::class),
    CreateUserRepository::class => get(DoctrineCreateUserRepository::class),
    SessionRepository::class => get(PHPSessionRepository::class),

    // Slim
    ServerRequestFactoryInterface::class => get(ServerRequestFactory::class),

    // Security
    PasswordHasherInterface::class => function() {
        $passwordHasherFactory = new PasswordHasherFactory([
            User::class => ['algorithm' => 'auto']
        ]);

        return $passwordHasherFactory->getPasswordHasher(User::class);
    },

    // Lcobucci
    Configuration::class => function () {
        return Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::base64Encoded($_ENV['JWT_KEY'])
        );
    },
    TokenHandler::class => get(LcobucciTokenHandler::class)
];
