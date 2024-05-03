<?php

namespace FHPlatform\Component\Config\Config\Provider;

use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorEntityInterface;
use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorEntityRelatedInterface;
use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorIndexInterface;
use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorEntityRelatedTrait;
use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorEntityTrait;
use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorIndexTrait;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderBaseInterface;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderEntityInterface;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderEntityRelatedInterface;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderIndexInterface;
use FHPlatform\Component\Config\Util\Namer\IndexNamer;

abstract class ProviderEntity implements ProviderBaseInterface, ProviderIndexInterface, ProviderEntityRelatedInterface, ProviderEntityInterface, DecoratorIndexInterface, DecoratorEntityRelatedInterface, DecoratorEntityInterface
{
    use DecoratorIndexTrait;
    use DecoratorEntityRelatedTrait;
    use DecoratorEntityTrait;

    public function getIndexName(string $className): string
    {
        // TODO add default decorator
        return (new IndexNamer())->getName($className);
    }

    public function getClassName(): string
    {
        // TODO
        throw new \Exception('not implemented.');
    }

    public function getConnection(): string
    {
        return 'default';
    }
}
