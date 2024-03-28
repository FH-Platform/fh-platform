<?php

namespace FHPlatform\Component\Config\Config\Decorator\Trait;

trait DecoratorBaseTrait
{
    public function priority(): int
    {
        return 0;
    }

    public function getConfigAdditional(array $config): array
    {
        return $config;
    }
}
