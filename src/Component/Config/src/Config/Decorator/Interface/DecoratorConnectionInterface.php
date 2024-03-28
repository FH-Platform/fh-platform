<?php

namespace FHPlatform\Component\Config\Config\Decorator\Interface;

use FHPlatform\Component\Config\DTO\Connection;

interface DecoratorConnectionInterface extends DecoratorBaseInterface
{
    public function getConfigAdditionalPreIndex(Connection $connection, array $config): array;

    public function getConfigAdditionalPostIndex(Connection $connection, array $config): array;
}
