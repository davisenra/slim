<?php

declare(strict_types=1);

use Slim\App;
use Slim\ResponseEmitter;
use Slim\Factory\ServerRequestCreatorFactory;

ignore_user_abort(true);

$app = (require __DIR__ . '/../bootstrap/app.php')->get(App::class);

$handler = static function () use ($app) {
    $serverRequestCreator = ServerRequestCreatorFactory::create();
    $request = $serverRequestCreator->createServerRequestFromGlobals();
    $response = $app->handle($request);
    $responseEmitter = new ResponseEmitter();
    $responseEmitter->emit($response);
};

for ($nbRequests = 0, $running = true; isset($_SERVER['MAX_REQUESTS']) && ($nbRequests < ((int)$_SERVER['MAX_REQUESTS'])) && $running; ++$nbRequests) {
    $running = \frankenphp_handle_request($handler);
    gc_collect_cycles();
}