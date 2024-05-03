<?php

namespace FHPlatform\Component\Config\Config\Provider;

use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorEntityRelatedTrait;
use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorEntityTrait;
use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorIndexTrait;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderEntityInterface;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderEntityRelatedInterface;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderIndexInterface;
use FHPlatform\Component\Config\Util\Namer\IndexNamer;

abstract class ProviderEntity implements ProviderIndexInterface, ProviderEntityRelatedInterface, ProviderEntityInterface
{
    use DecoratorIndexTrait;
    use DecoratorEntityRelatedTrait;
    use DecoratorEntityTrait;

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
