<?php

namespace FHPlatform\Component\Config\Config\Decorator\Interface;

use FHPlatform\Component\Config\DTO\Connection;

interface DecoratorConnectionInterface extends DecoratorBaseInterface
{
    public function getConnectionConfigAdditionalPreIndex(Connection $connection, array $config): array;

    public function getConnectionConfigAdditionalPostIndex(Connection $connection, array $config): array;
}
