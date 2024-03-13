<?php

namespace FHPlatform\ConfigBundle\Tag\Decorator\Trait;

trait DecoratorIndexTrait
{
    public function getIndexSettings(string $className, array $settings): array
    {
        return $settings;
    }

    public function getIndexMapping(string $className, array $mapping): array
    {
        return $mapping;
    }

    public function getIndexMappingItem(string $className, array $mappingItem, string $key, ?string $type): array
    {
        return $mappingItem;
    }
}
