<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env');

return (new ContainerBuilder())
    ->addDefinitions(require_once __DIR__ . '/../config/container.php')
    ->build();
