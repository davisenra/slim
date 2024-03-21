<?php

namespace Tests\Traits;

use Slim\App;
use DI\ContainerBuilder;

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
        $container = (new ContainerBuilder())
            ->addDefinitions(require __DIR__.'/../../config/container.php')
            ->build();

        $this->app = $container->get(App::class);

        $this->setUpContainer($container);

        /** @phpstan-ignore-next-line */
        if (method_exists($this, 'setUpDatabase')) {
            $this->setUpDatabase(__DIR__.'/../../resources/schema/schema.sql');
        }
    }
}
