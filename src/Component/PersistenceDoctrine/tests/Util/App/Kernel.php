<?php

namespace FHPlatform\Component\PersistenceDoctrine\Tests\Util\App;

use FHPlatform\Bundle\TestsBundle\Tests\Util\BaseKernelTest;
use Symfony\Component\Config\Loader\LoaderInterface;

class Kernel extends BaseKernelTest
{
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__.'/config.yml');
    }

    public function getProjectDir(): string
    {
        return __DIR__.'/../';
    }
}
