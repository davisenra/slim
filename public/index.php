<?php

declare(strict_types=1);

use Slim\App;

(require __DIR__ . '/../bootstrap/app.php')
    ->get(App::class)
    ->run();
