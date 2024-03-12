<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Interface;

use FHPlatform\ConfigBundle\Service\Sorter\Interface\PriorityInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('fh_platform.config.tagged.decorator.entity_related')]
interface DecoratorEntityRelatedInterface extends PriorityInterface
{
    public function getEntityRelatedEntities(mixed $entity, array $entitiesRelated): array;
}
