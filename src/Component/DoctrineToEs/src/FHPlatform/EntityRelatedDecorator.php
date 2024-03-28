<?php

namespace FHPlatform\Component\DoctrineToEs\FHPlatform;

use FHPlatform\Component\Config\Config\Decorator\DecoratorEntityRelated;
use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\DoctrineToEs\Builder\EntitiesRelatedBuilder;

class EntityRelatedDecorator extends DecoratorEntityRelated
{
    public function __construct(
        private readonly EntitiesRelatedBuilder $entitiesRelatedBuilder,
    ) {
    }

    public function priority(): int
    {
        return -100;
    }

    public function getEntityRelatedEntities(Connection $connection, mixed $entity, array $changedFields, array $entitiesRelated): array
    {
        // TODO cache updating map
        $updatingMap = $connection->getConfigAdditionalPostIndex()['doctrine_updating_map'] ?? [];

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
