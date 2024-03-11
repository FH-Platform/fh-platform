<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Interface;

use FHPlatform\ConfigBundle\Util\Sorter\Interface\PriorityInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('symfony_es.decorator.entity_related')]
interface DecoratorEntityRelatedInterface extends PriorityInterface
{
    public function getEntityRelatedEntities(mixed $entity, array $entitiesRelated): array;
}

