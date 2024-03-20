<?php

declare(strict_types=1);

use Slim\App;
use App\Controller\HealthCheckController;

return function (App $app) {
    $app->get('/healthcheck', HealthCheckController::class);
};
