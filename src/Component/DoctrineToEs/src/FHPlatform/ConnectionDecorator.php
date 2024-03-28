<?php

namespace FHPlatform\Component\DoctrineToEs\FHPlatform;

use FHPlatform\Component\Config\Config\Decorator\DecoratorConnection;
use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\DoctrineToEs\Builder\UpdatingMapBuilder;

class ConnectionDecorator extends DecoratorConnection
{
    public function __construct(
        private readonly UpdatingMapBuilder $updatingMapBuilder,
    ) {
    }

    public function priority(): int
    {
        return -100;
    }

    public function getConfigAdditionalPostIndex(Connection $connection, array $config): array
    {
        $classNames = [];
        foreach ($connection->getIndexes() as $index) {
            $configDoctrineToEs = $index->getConfigAdditional()['doctrine_to_es'];

            if (null !== $configDoctrineToEs) {
                $classNames[$index->getClassName()] = $configDoctrineToEs;
            }
        }

        // TODO cache updating map
        $updatingMap = $this->updatingMapBuilder->build($classNames);

        $config['doctrine_updating_map'] = $updatingMap;

        return $config;
    }
}
