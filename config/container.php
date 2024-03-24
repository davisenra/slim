<?php

declare(strict_types=1);

use App\Middleware\CorsMiddleware;
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
use Symfony\Component\Dotenv\Dotenv;

return [

    App::class => function (Container $container): App {
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__ . '/../.env');

        $app = AppFactory::createFromContainer($container);

        $routeCollector = $app->getRouteCollector();
        $routeCollector->setCacheFile(__DIR__ . '/../var/routes.cache');

        $displayErrors = $_ENV['APP_ENV'] === 'development';
        $app->addErrorMiddleware(
            displayErrorDetails: $displayErrors,
            logErrors: true,
            logErrorDetails: true,
            logger: $container->get(LoggerInterface::class)
        );
        $app->add(CorsMiddleware::class);
        $app->addRoutingMiddleware();

        (require __DIR__ . '/routes.php')($app);

        return $app;
    },

    ServerRequestFactoryInterface::class => function (Container $container) {
        return $container->get(Psr17Factory::class);
    },

    LoggerInterface::class => function () {
        return new Logger(
            name: 'app',
            handlers: [
                new RotatingFileHandler(sprintf('%s/app.log', __DIR__ . '/../var/')),
            ],
            processors: [],
        );
    },

    EntityManagerInterface::class => function () {
        $config = ORMSetup::createAttributeMetadataConfiguration(
            paths: [__DIR__ . '/../src/Entity'],
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
