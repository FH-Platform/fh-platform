<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function getCacheDir(): string
    {
        return __DIR__.'/../../../var/cache';
    }

    public function getLogDir(): string
    {
        return __DIR__.'/../../../var/logs';
    }

    public function getProjectDir(): string
    {
        return __DIR__.'/../';
    }
}
