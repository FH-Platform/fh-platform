<?php

namespace FHPlatform\Component\PersistenceEloquent\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TestCase extends KernelTestCase
{
    protected function setUp(): void
    {
        // (1) boot the Symfony kernel
        self::bootKernel();

        // (2) use static::getContainer() to access the service container
        $this->container = static::getContainer();
    }
}
