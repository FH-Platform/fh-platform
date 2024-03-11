<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Interface;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('symfony_es.decorator.entity')]
interface EntityInterface
{
    public function getEntityData($entity, array $data): array;

    public function getEntityShouldBeIndexed($entity, bool $shouldBeIndexed): bool;

    public function getEntityRelatedEntities($entity, $entitiesRelated): array;
}
