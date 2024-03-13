<?php

namespace FHPlatform\ConfigBundle\Tests;

use Doctrine\ORM\EntityManagerInterface;
use FHPlatform\TestsBundle\Tests\Util\CommandHelper;
use Symfony\Component\DependencyInjection\Container;

class TestCase extends \FHPlatform\TestsBundle\Tests\TestCase
{
    private static array $events = [];

    protected Container $container;
    protected CommandHelper $commandHelper;
    protected EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->prepareContainer();
    }
}
