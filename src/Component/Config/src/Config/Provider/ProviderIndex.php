<?php

namespace FHPlatform\Component\Config\Config\Provider;

use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorIndexTrait;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderIndexInterface;
use FHPlatform\Component\Config\Util\Namer\IndexNamer;

abstract class ProviderIndex implements ProviderIndexInterface
{
    use DecoratorIndexTrait;

    public function getIndexName(string $className): string
    {
        // TODO add default decorator
        return (new IndexNamer())->getName($className);
    }

    public function getIndexClassName(): string
    {
        // TODO
        throw new \Exception('not implemented.');
    }

    public function getIndexConnectionName(): string
    {
        return 'default';
    }
}
