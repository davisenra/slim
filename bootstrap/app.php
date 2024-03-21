<?php

declare(strict_types=1);

use Slim\App;
use DI\ContainerBuilder;

require __DIR__.'/../vendor/autoload.php';

$container = (new ContainerBuilder())
    ->addDefinitions(require_once __DIR__.'/../config/container.php')
    ->build();

return $container->get(App::class);
