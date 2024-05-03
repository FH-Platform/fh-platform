<?php

namespace FHPlatform\Component\Config\Config\Decorator\Trait;

use FHPlatform\Component\Config\DTO\Connection;

trait DecoratorConnectionTrait
{
    use DecoratorBaseTrait;

    public function getConnectionConfigAdditionalPreIndex(Connection $connection, array $config): array
    {
        return $config;
    }

    public function getConnectionConfigAdditionalPostIndex(Connection $connection, array $config): array
    {
        return $config;
    }
}
