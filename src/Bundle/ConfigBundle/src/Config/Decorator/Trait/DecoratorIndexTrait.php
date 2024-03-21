<?php

namespace FHPlatform\Bundle\ConfigBundle\Config\Decorator\Trait;

use FHPlatform\Bundle\ConfigBundle\DTO\Index;

trait DecoratorIndexTrait
{
    public function getIndexSettings(Index $index, array $settings): array
    {
        return $settings;
    }

    public function getIndexMapping(Index $index, array $mapping): array
    {
        return $mapping;
    }

    public function getIndexMappingItem(Index $index, array $mappingItem, string $mappingItemKey, ?string $mappingItemType): array
    {
        return $mappingItem;
    }
}
