<?php

namespace Tests\Traits;

use Slim\App;
use Symfony\Component\Console\Application;

trait AppTestTrait
{
    use ContainerTestTrait;
    use HttpJsonTestTrait;
    use HttpTestTrait;
    use CommandTestTrait;

    protected App $app;
    protected Application $cliApp;

    /**
     * Before each test.
     */
    protected function setUp(): void
    {
        $this->setUpApp();
    }

    protected function setUpApp(): void
    {
        $container = require __DIR__ . '/../../bootstrap/app.php';

        $this->app = $container->get(App::class);
        $this->cliApp = $container->get(Application::class);
        $this->setUpContainer($container);
    }
}
