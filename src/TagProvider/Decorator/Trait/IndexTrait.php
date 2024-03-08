<?php

namespace FHPlatform\ConfigBundle\TagProvider\Decorator\Trait;

use FHPlatform\ConfigBundle\Util\Namer\IndexNamer;

trait IndexTrait
{
    // TODO add getConnection

    public function getIndexName(string $className, string $name): string
    {
        // TODO add default decorator
        if (0 === $this->priority() && $name) {
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
