<?php

namespace FHPlatform\ConfigBundle\Tag\Provider\Trait;

use FHPlatform\ConfigBundle\Service\Namer\IndexNamer;

trait ProviderIndexTrait
{
    public function getConnection(): string
    {
        return 'default';
    }

    public function getIndexName(string $className): string
    {
        // TODO add default decorator
        return (new IndexNamer())->getName($className);
    }
}
