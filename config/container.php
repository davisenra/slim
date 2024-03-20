<?php

declare(strict_types=1);

use Slim\App;
use DI\Container;
use Nyholm\Psr7\Factory\Psr17Factory;
use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestFactoryInterface;

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

];