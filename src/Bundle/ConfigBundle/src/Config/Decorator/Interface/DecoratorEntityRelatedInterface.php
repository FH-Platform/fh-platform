<?php

namespace FHPlatform\ConfigBundle\Config\Decorator\Interface;

use FHPlatform\ConfigBundle\Util\Sorter\Interface\PriorityInterface;

interface DecoratorEntityRelatedInterface extends PriorityInterface
{
    public function getEntityRelatedEntities(mixed $entity, array $entitiesRelated): array;
}
