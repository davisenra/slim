<?php

namespace Tests\Integration;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Tests\Traits\AppTestTrait;

class HealthCheckControllerTest extends TestCase
{
    use AppTestTrait;

    #[Test]
    public function itReturnsASuccessfulResponse(): void
    {
        $request = $this->createRequest('GET', '/healthcheck');
        $response = $this->app->handle($request);

        $this->assertSame(200, $response->getStatusCode());
    }
}
