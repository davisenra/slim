<?php

declare(strict_types=1);

use App\Controller\FileUploadController;
use App\Controller\HealthCheckController;
use Nyholm\Psr7\Response;
use Slim\App;

return function (App $app) {
    $app->get('/healthcheck', HealthCheckController::class);
    $app->post('/upload', FileUploadController::class);

    $app->options('/{routes:.+}', fn ($request, $response) => $response);

    $app->map(
        ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'],
        '/{routes:.+}',
        fn () => new Response(404)
    );
};
