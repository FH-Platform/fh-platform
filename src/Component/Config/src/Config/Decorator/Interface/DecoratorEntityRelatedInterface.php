<?php

namespace FHPlatform\Component\Config\Config\Decorator\Interface;

use FHPlatform\Component\Config\DTO\Connection;

interface DecoratorEntityRelatedInterface extends DecoratorBaseInterface
{
    public function getEntityRelatedEntities(Connection $connection, mixed $entity, array $changedFields, array $entitiesRelated): array;
}
