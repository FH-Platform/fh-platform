<?php

namespace FHPlatform\Component\Config\Config\Provider;

use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorEntityRelatedInterface;
use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorEntityRelatedTrait;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderBaseInterface;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderEntityRelatedInterface;

abstract class ProviderEntityRelated implements ProviderBaseInterface, ProviderEntityRelatedInterface, DecoratorEntityRelatedInterface
{
    use DecoratorEntityRelatedTrait;

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
