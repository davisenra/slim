<?php

declare(strict_types=1);

use Slim\App;
use DI\Container;
use Monolog\Logger;
use Doctrine\ORM\ORMSetup;
use Psr\Log\LoggerInterface;
use Slim\Factory\AppFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;
use Symfony\Component\Dotenv\Dotenv;
use Nyholm\Psr7\Factory\Psr17Factory;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Handler\RotatingFileHandler;
use Psr\Http\Message\ServerRequestFactoryInterface;

return [

    App::class => function (Container $container): App {
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__.'/../.env');

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
                'driver' => $_ENV['DB_DRIVER'],
                'dbname' => sprintf('%s/../var/%s', __DIR__, $_ENV['DB_NAME']),
                'user' => $_ENV['DB_USER'],
                'password' => $_ENV['DB_PASSWORD'],
            ],
            config: $config,
        );

        return new EntityManager($connection, $config);
    },

];
