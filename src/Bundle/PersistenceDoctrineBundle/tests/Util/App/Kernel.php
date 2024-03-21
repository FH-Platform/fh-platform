<?php

namespace FHPlatform\Bundle\PersistenceDoctrineBundle\Tests\Util\App;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use FHPlatform\Bundle\PersistenceBundle\PersistenceBundle;
use FHPlatform\Bundle\PersistenceDoctrineBundle\PersistenceDoctrineBundle;
use FHPlatform\Bundle\SymfonyBridgeBundle\SymfonyBridgeBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    public function registerBundles(): array
    {
        return [
            new FrameworkBundle(),
            new DoctrineBundle(),

            new PersistenceBundle(),
            new PersistenceDoctrineBundle(),
            new SymfonyBridgeBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__.'/config.yml');
    }

    public function getCacheDir(): string
    {
        return 'var/cache';
    }

    public function getLogDir(): string
    {
        return 'var/logs';
    }

    public function getProjectDir(): string
    {
        return __DIR__.'/../';
    }
}