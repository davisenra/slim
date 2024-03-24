<?php

namespace Tests\Application\Middleware;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Tests\Traits\AppTestTrait;

class CorsMiddlewareTest extends TestCase
{
    use AppTestTrait;

    #[Test]
    public function itAddsCorsHeaders(): void
    {
        $request = $this->createJsonRequest('GET', '/healthcheck');
        $response = $this->app->handle($request);

        $this->assertEquals(
            $_ENV['DOMAIN'],
            $response->getHeaderLine('Access-Control-Allow-Origin')
        );
        $this->assertEquals(
            'X-Requested-With, Content-Type, Accept, Origin, Authorization',
            $response->getHeaderLine('Access-Control-Allow-Headers')
        );
        $this->assertEquals(
            'GET, POST, PUT, DELETE, PATCH, OPTIONS',
            $response->getHeaderLine('Access-Control-Allow-Methods')
        );
        $this->assertEquals(
            'true',
            $response->getHeaderLine('Access-Control-Allow-Credentials')
        );
    }
}
