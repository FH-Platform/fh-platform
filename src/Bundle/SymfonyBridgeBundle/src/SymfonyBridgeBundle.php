<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle;

use FHPlatform\Bundle\SymfonyBridgeBundle\Builder\Builder;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SymfonyBridgeBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        (new Builder())->build($container);
    }
}
