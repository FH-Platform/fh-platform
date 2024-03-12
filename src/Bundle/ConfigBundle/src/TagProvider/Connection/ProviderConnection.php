<?php

namespace FHPlatform\ConfigBundle\TagProvider\Connection;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('fh_platform.config.provider.connection')]
abstract class ProviderConnection
{
    public function getName(): string
    {
        return 'default';
    }

    abstract public function getIndexPrefix(): string;

    abstract public function getElasticaConfig(): array;
}
