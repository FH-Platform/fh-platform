<?php

namespace FHPlatform\Component\Config\Config\Provider\Trait;

use FHPlatform\Component\Config\Util\Namer\IndexNamer;

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
