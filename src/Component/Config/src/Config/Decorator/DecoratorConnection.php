<?php

namespace FHPlatform\Component\Config\Config\Decorator;

use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorConnectionInterface;
use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorBaseTrait;
use FHPlatform\Component\Config\DTO\Connection;

abstract class DecoratorConnection implements DecoratorConnectionInterface
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
