<?php

namespace FHPlatform\Bundle\PersistenceBundle;

use FHPlatform\Bundle\PersistenceBundle\Builder\PersistenceBuilder;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PersistenceBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        (new PersistenceBuilder())->build($container);
    }
}
