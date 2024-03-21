<?php

declare(strict_types=1);

use App\Controller\HealthCheckController;
use Slim\App;

return function (App $app) {
    $app->get('/healthcheck', HealthCheckController::class);
};
