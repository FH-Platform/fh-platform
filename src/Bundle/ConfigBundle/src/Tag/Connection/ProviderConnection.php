<?php

namespace FHPlatform\ConfigBundle\Tag\Connection;

abstract class ProviderConnection
{
    public function getName(): string
    {
        return 'default';
    }

    abstract public function getIndexPrefix(): string;

    abstract public function getClientConfig(): array;

    public function getAdditionalConfig(): array
    {
        return [];
    }
}
