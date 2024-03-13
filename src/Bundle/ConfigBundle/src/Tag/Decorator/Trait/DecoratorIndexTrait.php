<?php

namespace FHPlatform\ConfigBundle\Tag\Decorator\Trait;

use FHPlatform\ConfigBundle\DTO\Index;

trait DecoratorIndexTrait
{
    public function getIndexSettings(Index $index, array $settings): array
    {
        return $settings;
    }

    public function getIndexMapping(string $className, array $mapping): array
    {
        return $mapping;
    }

    public function getIndexMappingItem(string $className, array $mappingItem, string $mappingItemKey, ?string $mappingItemType): array
    {
        return $mappingItem;
    }
}
