<?php

namespace FHPlatform\Component\Config\Config\Decorator\Trait;

use FHPlatform\Component\Config\DTO\Index;

trait DecoratorIndexTrait
{
    public function getIndexSettings(Index $index, array $settings): array
    {
        return $settings;
    }

    public function getIndexConfigAdditional(Index $index, array $config): array
    {
        return $config;
    }

    public function getIndexMapping(Index $index, array $mapping): array
    {
        return $mapping;
    }

    public function getIndexMappingItem(Index $index, mixed $mappingItem, mixed $mappingItemKey, mixed $mappingItemType): mixed
    {
        return $mappingItem;
    }

    public function getIndexEntityChangedFields(Index $index, array $changedFields): array
    {
        return $changedFields;
    }
}
