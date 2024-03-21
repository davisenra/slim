<?php

namespace Tests\Infrastructure;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Tests\Traits\AppTestTrait;

class DoctrineTest extends TestCase
{
    use AppTestTrait;

    #[Test]
    public function itCanGetAnEntityManagerInstance(): void
    {
        $entityManager = $this->container->get(EntityManagerInterface::class);

        $this->assertInstanceOf(EntityManager::class, $entityManager);
    }
}
