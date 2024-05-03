<?php

namespace FHPlatform\Component\Config\Config\Provider;

use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorEntityRelatedTrait;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderEntityRelatedInterface;

abstract class ProviderEntityRelated implements ProviderEntityRelatedInterface
{
    use DecoratorEntityRelatedTrait;
}
