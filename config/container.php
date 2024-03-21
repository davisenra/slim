<?php

declare(strict_types=1);

use DI\Container;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Factory\AppFactory;

return [

    App::class => function (Container $container): App {
        $app = AppFactory::createFromContainer($container);

        $app->addErrorMiddleware(
            displayErrorDetails: true,
            logErrors: true,
            logErrorDetails: true,
        );

        (require __DIR__.'/routes.php')($app);

        return $app;
    },

    ServerRequestFactoryInterface::class => function (Container $container) {
        return $container->get(Psr17Factory::class);
    },

    LoggerInterface::class => function (Container $container) {
        return new Logger(
            name: 'app',
            handlers: [
                new RotatingFileHandler(sprintf('%s/app.log', __DIR__.'/../var/')),
            ],
            processors: [],
        );
    },

    EntityManagerInterface::class => function (Container $container) {
        $config = ORMSetup::createAttributeMetadataConfiguration(
            paths: [__DIR__.'/../src/Entity'],
            isDevMode: true,
        );

        $connection = DriverManager::getConnection(
            params: [
                'driver' => 'pdo_sqlite',
                'dbname' => __DIR__ . '/../var/database.sqlite',
                'user' => 'root',
                'password' => '',
            ],
            config: $config,
        );

        return new EntityManager($connection, $config);
    },

];
