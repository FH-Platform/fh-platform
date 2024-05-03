<?php

namespace FHPlatform\Component\Config\Config\Decorator\Trait;

use FHPlatform\Component\Config\DTO\Index;

trait DecoratorEntityTrait
{
    use DecoratorBaseTrait;

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
