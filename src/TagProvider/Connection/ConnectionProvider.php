<?php

namespace FHPlatform\ConfigBundle\TagProvider\Connection;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('symfony_es.connection_provider')]
abstract class ConnectionProvider
{
    public function getName(): string
    {
        return 'default';
    }

    abstract public function getIndexPrefix(): string;

    abstract public function getElasticaConfig(): array;
}
