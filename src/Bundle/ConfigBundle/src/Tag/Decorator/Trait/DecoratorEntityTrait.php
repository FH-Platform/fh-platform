<?php

namespace FHPlatform\ConfigBundle\Tag\Decorator\Trait;

trait DecoratorEntityTrait
{
    public function getEntityData(mixed $entity, array $data, array $mapping): array
    {
        return $data;
    }

    public function getEntityDataItem(mixed $entity, mixed $dataItem, ?array $mappingItem, ?string $mappingItemKey, ?string $mappingItemType): mixed
    {
        return $dataItem;
    }

    public function getEntityShouldBeIndexed($entity, bool $shouldBeIndexed): bool
    {
        return $shouldBeIndexed;
    }
}
