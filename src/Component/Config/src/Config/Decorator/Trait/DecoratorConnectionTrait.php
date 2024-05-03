<?php

namespace FHPlatform\Component\Config\Config\Decorator\Trait;

use FHPlatform\Component\Config\DTO\Connection;

trait DecoratorConnectionTrait
{
    use DecoratorBaseTrait;

    public function getConfigAdditionalPreIndex(Connection $connection, array $config): array
    {
        return $config;
    }

    public function getConfigAdditionalPostIndex(Connection $connection, array $config): array
    {
        return $config;
    }
}
