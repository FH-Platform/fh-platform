<?php

namespace FHPlatform\UtilBundle\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Container;

class TestCase extends KernelTestCase
{
    protected Container $container;
    protected EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        // (1) boot the Symfony kernel
        self::bootKernel();

        // (2) use static::getContainer() to access the service container
        $this->container = static::getContainer();

        // (3) - EntityManagerInterface
        $this->entityManager = $this->container->get(EntityManagerInterface::class);

        $this->migrateDb();
    }

    private function migrateDb()
    {
        // updating a schema in sqlite database
        $metaData = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->updateSchema($metaData);
    }
}
