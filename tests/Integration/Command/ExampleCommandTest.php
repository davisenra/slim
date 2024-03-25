<?php

namespace Tests\Integration\Command;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Tests\Traits\AppTestTrait;

class ExampleCommandTest extends TestCase
{
    use AppTestTrait;

    #[Test]
    public function itCanBeCalled(): void
    {
        $commandTester = $this->callCommand('app:example-command');
        $commandTester->execute([]);
        $output = $commandTester->getDisplay();

        $commandTester->assertCommandIsSuccessful();
        $this->assertStringContainsString('Hello from command!', $output);
    }
}
