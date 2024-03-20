<?php

declare(strict_types=1);

use Slim\App;
use DI\Container;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Nyholm\Psr7\Factory\Psr17Factory;
use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Log\LoggerInterface;

return [

    App::class => function (Container $container): App {
        $app = AppFactory::createFromContainer($container);

        $app->addErrorMiddleware(
            displayErrorDetails: true,
            logErrors: true,
            logErrorDetails: true,
        );

        (require __DIR__ . '/routes.php')($app);

        return $app;
    },

    ServerRequestFactoryInterface::class => function (Container $container) {
        return $container->get(Psr17Factory::class);
    },

    LoggerInterface::class => function (Container $container) {
        return new Logger(
            name: 'app',
            handlers: [new RotatingFileHandler(
                sprintf('%s/app.log', __DIR__ . '/../var/')
            )],
            processors: [],
        );
    },

];
