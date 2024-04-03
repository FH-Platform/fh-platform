<?php

namespace FHPlatform\Bundle\EventManagerBundle;

use FHPlatform\Bundle\EventManagerBundle\Builder\EventManagerBuilder;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EventManagerBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        (new EventManagerBuilder())->build($container);
    }
}
