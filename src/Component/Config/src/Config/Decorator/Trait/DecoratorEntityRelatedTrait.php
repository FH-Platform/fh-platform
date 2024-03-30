<?php

namespace FHPlatform\Component\Config\Config\Decorator\Trait;

use FHPlatform\Component\Config\DTO\Connection;

trait DecoratorEntityRelatedTrait
{
    public function getEntityRelatedEntities(Connection $connection, mixed $entity, string $type, array $changedFields, array $entitiesRelated): array
    {
        return $entitiesRelated;
    }
}
