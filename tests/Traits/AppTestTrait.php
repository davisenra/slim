<?php

namespace Tests\Traits;

use DI\ContainerBuilder;
use Slim\App;
use Symfony\Component\Dotenv\Dotenv;

trait AppTestTrait
{
    use ContainerTestTrait;
    use HttpJsonTestTrait;
    use HttpTestTrait;

    protected App $app;

    /**
     * Before each test.
     */
    protected function setUp(): void
    {
        $this->setUpApp();
    }

    protected function setUpApp(): void
    {
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__ . '/../../.env');

        $container = (new ContainerBuilder())
            ->addDefinitions(require __DIR__ . '/../../config/container.php')
            ->build();

        $this->app = $container->get(App::class);

        $this->setUpContainer($container);
    }
}
