<?php

namespace FHPlatform\ConfigBundle\Tag\Data\Decorator\Trait;

use FHPlatform\ConfigBundle\Service\Namer\IndexNamer;

trait DecoratorIndexTrait
{
    public function getConnection(): string
    {
        return 'default';
    }

    public function getIndexName(string $className, string $name): string
    {
        // TODO add default decorator
        if ($name) {
            return $name;
        }

        return (new IndexNamer())->getName($className);
    }

    public function getIndexSettings(string $className, array $settings): array
    {
        return $settings;
    }

    public function getIndexMapping(string $className, array $mapping): array
    {
        return $mapping;
    }
}
