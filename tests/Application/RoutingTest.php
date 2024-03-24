<?php

declare(strict_types=1);

namespace Application;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Tests\Traits\AppTestTrait;

class RoutingTest extends TestCase
{
    use AppTestTrait;

    #[Test]
    public function itLoadsAllRoutes(): void
    {
        $routes = $this->app
            ->getRouteCollector()
            ->getRoutes();

        $this->assertNotEmpty($routes);
    }

    #[Test]
    public function itGracefullyHandlesNotFoundRoutes(): void
    {
        $request = $this->createJsonRequest('GET', '/invalid-route');
        $response = $this->app->handle($request);

        $this->assertEquals(404, $response->getStatusCode());
    }
}
