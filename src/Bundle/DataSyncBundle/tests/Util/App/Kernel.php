<?php

namespace FHPlatform\Bundle\DataSyncBundle\Tests\Util\App;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use FHPlatform\Bundle\DataSyncBundle\DataSyncBundle;
use FHPlatform\Bundle\PersistenceBundle\PersistenceBundle;
use FHPlatform\Bundle\SymfonyBridgeBundle\SymfonyBridgeBundle;
use FHPlatform\Bundle\UtilBundle\UtilBundle;
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
