<?php

declare(strict_types=1);

use App\Middleware\CorsMiddleware;
use DI\Container;
use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Symfony\Component\Console\Application;

return [

    'DB_PARAMS' => function () {
        return [
            'driver' => $_ENV['DB_DRIVER'],
            'dbname' => $_ENV['DB_NAME'],
            'host' => $_ENV['DB_HOST'],
            'user' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASSWORD'],
            'path' => $_ENV['DB_DRIVER'] === 'pdo_sqlite'
                ? __DIR__ . '/../var/database.sqlite'
                : null,
        ];
    },

    App::class => function (Container $container): App {
        $isDevEnvironment = $_ENV['APP_ENV'] === 'development';

        $app = AppFactory::createFromContainer($container);

        if (!$isDevEnvironment) {
            $routeCollector = $app->getRouteCollector();
            $routeCollector->setCacheFile(__DIR__ . '/../var/routes.cache');
        }

        $app->add(CorsMiddleware::class);
        $app->addRoutingMiddleware();

        $errorMiddleware = $app->addErrorMiddleware(
            displayErrorDetails: $isDevEnvironment,
            logErrors: true,
            logErrorDetails: true,
            logger: $container->get(LoggerInterface::class)
        );

        $errorHandler = $errorMiddleware->getDefaultErrorHandler();
        $errorHandler->forceContentType('application/json');

        (require __DIR__ . '/routes.php')($app);

        return $app;
    },

    Application::class => function (Container $container) {
        $cli = new Application();
        $cli->setCatchExceptions(true);

        // Doctrine ORM CLI commands
        $entityManager = $container->get(EntityManagerInterface::class);
        $entityManagerProvider = new SingleManagerProvider($entityManager);
        ConsoleRunner::addCommands(
            cli: $cli,
            entityManagerProvider: $entityManagerProvider
        );

        // Doctrine Migrations CLI commands
        $entityManager = $container->get(EntityManagerInterface::class);
        $dependencyFactory = DependencyFactory::fromEntityManager(
            configurationLoader: new PhpFile(__DIR__ . '/../config/migrations.php'),
            emLoader: new ExistingEntityManager($entityManager),
        );

        $cli->addCommands(array(
            new Doctrine\Migrations\Tools\Console\Command\DumpSchemaCommand($dependencyFactory),
            new Doctrine\Migrations\Tools\Console\Command\ExecuteCommand($dependencyFactory),
            new Doctrine\Migrations\Tools\Console\Command\GenerateCommand($dependencyFactory),
            new Doctrine\Migrations\Tools\Console\Command\LatestCommand($dependencyFactory),
            new Doctrine\Migrations\Tools\Console\Command\ListCommand($dependencyFactory),
            new Doctrine\Migrations\Tools\Console\Command\MigrateCommand($dependencyFactory),
            new Doctrine\Migrations\Tools\Console\Command\RollupCommand($dependencyFactory),
            new Doctrine\Migrations\Tools\Console\Command\StatusCommand($dependencyFactory),
            new Doctrine\Migrations\Tools\Console\Command\SyncMetadataCommand($dependencyFactory),
            new Doctrine\Migrations\Tools\Console\Command\VersionCommand($dependencyFactory),
            new Doctrine\Migrations\Tools\Console\Command\DiffCommand($dependencyFactory)
        ));

        return $cli;
    },

    ServerRequestFactoryInterface::class => function (Container $container) {
        return $container->get(Psr17Factory::class);
    },

    LoggerInterface::class => function () {
        /** @var HandlerInterface[] $handlers */
        $handlers = [];
        $handlers[] = new StreamHandler('php://stdout', Level::Debug);

        if ($_ENV['APP_ENV'] === 'development') {
            $handlers[] = new RotatingFileHandler(sprintf('%s/app.log', __DIR__ . '/../var/'));
        }

        return new Logger(
            name: 'app',
            handlers: $handlers,
            processors: [],
        );
    },

    EntityManagerInterface::class => function (Container $container) {
        $config = ORMSetup::createAttributeMetadataConfiguration(
            paths: [__DIR__ . '/../src/Entity'],
            isDevMode: $_ENV['APP_ENV'] === 'development',
        );

        $connection = DriverManager::getConnection(
            params: $container->get('DB_PARAMS'),
            config: $config,
        );

        return new EntityManager($connection, $config);
    },

];
