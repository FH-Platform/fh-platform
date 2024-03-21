<?php

namespace FHPlatform\DataSyncBundle\Tests\Util\App;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use FHPlatform\DataSyncBundle\DataSyncBundle;
use FHPlatform\PersistenceBundle\PersistenceBundle;
use FHPlatform\SymfonyBridgeBundle\SymfonyBridgeBundle;
use FHPlatform\UtilBundle\UtilBundle;
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

            new SymfonyBridgeBundle(),
            new UtilBundle(),
            new DataSyncBundle(),
            new PersistenceBundle(),
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
