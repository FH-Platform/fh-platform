<?php

namespace FHPlatform\Component\Config\Config\Decorator\Interface;

interface DecoratorEntityRelatedInterface extends DecoratorBaseInterface
{
    public function getEntityRelatedEntities(mixed $entity, array $changedFields, array $entitiesRelated): array;
}
