<?php

namespace Tests\Traits;

use Symfony\Component\Console\Tester\CommandTester;

/**
 * Command Test Trait.
 */
trait CommandTestTrait
{
    protected function callCommand(string $commandName): CommandTester
    {
        $command = $this->cliApp->find($commandName);

        return new CommandTester($command);
    }
}
