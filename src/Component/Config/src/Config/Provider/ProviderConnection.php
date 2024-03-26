<?php

namespace FHPlatform\Component\Config\Config\Provider;

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

    public function getUpdatingMap(): array
    {
        return [];
    }
}
