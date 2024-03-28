<?php

namespace FHPlatform\Component\Config\Config\Provider;

use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorBaseInterface;
use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorBaseTrait;

abstract class ProviderConnection implements DecoratorBaseInterface
{
    use DecoratorBaseTrait;

    public function getName(): string
    {
        return 'default';
    }

    abstract public function getIndexPrefix(): string;

    abstract public function getClientConfig(): array;
}
