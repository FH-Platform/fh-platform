<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Interface;

use FHPlatform\ConfigBundle\Service\Sorter\Interface\PriorityInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('fh_platform.config.tagged.decorator.entity')]
interface DecoratorEntityInterface extends PriorityInterface
{
    public function getEntityData(mixed $entity, array $data): array;

    public function getEntityShouldBeIndexed($entity, bool $shouldBeIndexed): bool;

    public function getEntityRelatedEntities(mixed $entity, array $entitiesRelated): array;
}
