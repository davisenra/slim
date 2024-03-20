<?php

declare(strict_types=1);

use App\Controller\HealthCheckController;
use DI\Bridge\Slim\Bridge;
use DI\Container;

$container = new Container();
$app = Bridge::create($container);

$app->addErrorMiddleware(
    displayErrorDetails: true,
    logErrors: true,
    logErrorDetails: true,
);

$app->get('/healthcheck', HealthCheckController::class);

return $app;