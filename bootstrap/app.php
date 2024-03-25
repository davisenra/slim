<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env');

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(require __DIR__ . '/../config/container.php');

if ($_ENV['APP_ENV'] !== 'development') {
    $containerBuilder->enableCompilation(__DIR__ . '/../var/');
}

return $containerBuilder->build();
