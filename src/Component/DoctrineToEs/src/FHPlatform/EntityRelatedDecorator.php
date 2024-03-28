<?php

namespace FHPlatform\Component\DoctrineToEs\FHPlatform;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Config\Decorator\DecoratorEntityRelated;
use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\DoctrineToEs\Builder\EntitiesRelatedBuilder;
use FHPlatform\Component\DoctrineToEs\Builder\UpdatingMapBuilder;

class EntityRelatedDecorator extends DecoratorEntityRelated
{
    public function __construct(
        private readonly UpdatingMapBuilder $updatingMapBuilder,
        private readonly EntitiesRelatedBuilder $entitiesRelatedBuilder,
        private readonly ConnectionsBuilder $connectionsBuilder,
    ) {
    }

    public function priority(): int
    {
        return -100;
    }

    public function getEntityRelatedEntities(Connection $connection, mixed $entity, array $changedFields, array $entitiesRelated): array
    {
        $connections = $this->connectionsBuilder->build();

        $classNames = [];
        foreach ($connection->getIndexes() as $index) {
            $configDoctrineToEs = $index->getConfigAdditional()['doctrine_to_es'];

            if (null !== $configDoctrineToEs) {
                $classNames[$index->getClassName()] = $configDoctrineToEs;
            }
        }

        // TODO cache updating map
        $updatingMap = $this->updatingMapBuilder->build($classNames);

        // TODO clean and hash
        $entities = $this->entitiesRelatedBuilder->build($entity, $updatingMap, $changedFields);
        foreach ($entities as $relations => $entity) {
            foreach ($entity as $id => $entity2) {
                $entitiesRelated[] = $entity2;
            }
        }

        return $entitiesRelated;
    }
}
