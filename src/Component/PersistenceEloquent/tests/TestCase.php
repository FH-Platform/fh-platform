<?php

namespace FHPlatform\Component\PersistenceEloquent\Tests;

use FHPlatform\Bundle\TestsBundle\Tests\Util\CommandHelper;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TestCase extends KernelTestCase
{
    protected ContainerInterface $container;
    protected CommandHelper $commandHelper;

    protected function setUp(): void
    {
        // (1) boot the Symfony kernel
        self::bootKernel();

        // (2) use static::getContainer() to access the service container
        $this->container = static::getContainer();

        // (3) - CommandHelper
        $this->commandHelper = new CommandHelper(self::$kernel);
    }
}
