<?php

declare(strict_types=1);

namespace Tests\Application;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Tests\Traits\AppTestTrait;

class LoggerTest extends TestCase
{
    use AppTestTrait;

    #[Test]
    public function itCanGetALoggerInterfaceInstance(): void
    {
        $logger = $this->container->get(LoggerInterface::class);

        $this->assertInstanceOf(LoggerInterface::class, $logger);
    }
}
