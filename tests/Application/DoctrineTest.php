<?php

declare(strict_types=1);

namespace Tests\Application;

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
        /** @var EntityManager $entityManager */
        $entityManager = $this->container->get(EntityManagerInterface::class);
        $entityManager->getConnection()->beginTransaction(); // start the connection

        $this->assertInstanceOf(EntityManager::class, $entityManager);
        $this->assertTrue($entityManager->getConnection()->isConnected());
    }
}
