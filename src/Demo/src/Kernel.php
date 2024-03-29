<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function getCacheDir(): string
    {
        return '../../var/cache';
    }

    public function getLogDir(): string
    {
        return '../../var/logs';
    }

    /*public function getProjectDir(): string
    {
        return __DIR__.'/../';
    }*/
}
