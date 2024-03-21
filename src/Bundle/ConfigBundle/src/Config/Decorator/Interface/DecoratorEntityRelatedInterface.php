<?php

namespace FHPlatform\Bundle\ConfigBundle\Config\Decorator\Interface;

use FHPlatform\Bundle\ConfigBundle\Util\Sorter\Interface\PriorityInterface;

interface DecoratorEntityRelatedInterface extends PriorityInterface
{
    public function getEntityRelatedEntities(mixed $entity, array $entitiesRelated): array;
}
