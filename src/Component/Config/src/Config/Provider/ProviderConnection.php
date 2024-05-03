<?php

namespace FHPlatform\Component\Config\Config\Provider;

use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorConnectionInterface;
use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorConnectionTrait;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderBaseInterface;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderConnectionInterface;

abstract class ProviderConnection implements ProviderBaseInterface, ProviderConnectionInterface, DecoratorConnectionInterface
{
    use DecoratorConnectionTrait;

    public function getName(): string
    {
        return 'default';
    }

    abstract public function getIndexPrefix(): string;

    abstract public function getClientConfig(): array;
}
