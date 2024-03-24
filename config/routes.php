<?php

declare(strict_types=1);

use App\Controller\HealthCheckController;
use Slim\App;
use Slim\Exception\HttpNotFoundException;

return function (App $app) {
    $app->get('/healthcheck', HealthCheckController::class);

    $app->options('/{routes:.+}', fn ($request, $response) => $response);

    $app->map(
        ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'],
        '/{routes:.+}',
        fn ($request) => throw new HttpNotFoundException($request)
    );
};
