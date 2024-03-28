<?php

namespace FHPlatform\Component\Config\Config\Provider;

use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorBaseInterface;
use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorConnectionTrait;

abstract class ProviderConnection implements DecoratorBaseInterface
{
    use DecoratorConnectionTrait;

    public function getName(): string
    {
        return 'default';
    }

    abstract public function getIndexPrefix(): string;

    abstract public function getClientConfig(): array;
}
