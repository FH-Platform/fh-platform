<?php

namespace FHPlatform\Component\Config\Config\Decorator;

use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorConnectionInterface;
use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorBaseTrait;

abstract class DecoratorConnection implements DecoratorConnectionInterface
{
    use DecoratorBaseTrait;

    public function getConfigAdditional(array $config): array
    {
        return $config;
    }
}
