<?php

namespace FHPlatform\ConfigBundle\Tag\Data\Decorator\Trait;

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
}
