<?php

namespace FHPlatform\Bundle\ConfigBundle\Config\Decorator\Trait;

use FHPlatform\Bundle\ConfigBundle\DTO\Index;

trait DecoratorEntityTrait
{
    public function getEntityData(Index $index, mixed $entity, array $data): array
    {
        return $data;
    }

    public function getEntityDataItem(Index $index, mixed $entity, mixed $dataItem, ?array $mappingItem, ?string $mappingItemKey, ?string $mappingItemType): mixed
    {
        return $dataItem;
    }

    public function getEntityShouldBeIndexed(Index $index, $entity, bool $shouldBeIndexed): bool
    {
        return $shouldBeIndexed;
    }
}
