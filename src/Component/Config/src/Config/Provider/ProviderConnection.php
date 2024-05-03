<?php

namespace FHPlatform\Component\Config\Config\Provider;

use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorConnectionTrait;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderConnectionInterface;

abstract class ProviderConnection implements ProviderConnectionInterface
{
    use DecoratorConnectionTrait;

    public function getConnectionName(): string
    {
        return 'default';
    }

    public function getConnectionIndexPrefix(): string
    {
        return 'prefix_';
    }

    public function getConnectionClientConfig(): array
    {
        return [];
    }
}
