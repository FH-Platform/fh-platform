<?php

namespace FHPlatform\UtilBundle\Tests\Util\App;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
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

            new UtilBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__.'/config/config.yml');
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
