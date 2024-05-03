<?php

namespace FHPlatform\Component\Config\Config\Provider;

use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorConnectionTrait;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderConnectionInterface;

abstract class ProviderConnection implements ProviderConnectionInterface
{
    use DecoratorConnectionTrait;

    public function getName(): string
    {
        return 'default';
    }

    abstract public function getIndexPrefix(): string;

    abstract public function getClientConfig(): array;
}
