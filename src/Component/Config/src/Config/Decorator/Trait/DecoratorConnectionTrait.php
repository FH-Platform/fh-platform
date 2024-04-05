<?php

namespace FHPlatform\Component\Config\Config\Decorator\Trait;

trait DecoratorConnectionTrait
{
    use DecoratorBaseTrait;

    public function getConnectionConfigAdditional(array $config): array
    {
        return $config;
    }
}
