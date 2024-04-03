<?php

namespace FHPlatform\Bundle\TestsBundle\Tests\Util;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use FHPlatform\Bundle\EventManagerBundle\EventManagerBundle;
use FHPlatform\Bundle\SymfonyBridgeBundle\SymfonyBridgeBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class BaseKernelTest extends BaseKernel
{
    public function registerBundles(): array
    {
        return [
            new FrameworkBundle(),
            new DoctrineBundle(),
            new MonologBundle(),

            new EventManagerBundle(),
            new SymfonyBridgeBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__.'/config.yml');
    }

    public function getProjectDir(): string
    {
        return __DIR__.'/../';
    }

    public function getCacheDir(): string
    {
        return 'var/cache';
    }

    public function getLogDir(): string
    {
        return 'var/logs';
    }
}
