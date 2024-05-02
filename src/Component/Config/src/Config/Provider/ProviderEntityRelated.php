<?php

namespace FHPlatform\Component\Config\Config\Provider;

use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorBaseTrait;
use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorEntityRelatedTrait;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderEntityRelatedInterface;
use FHPlatform\Component\Config\Config\Provider\Trait\ProviderBaseTrait;

abstract class ProviderEntityRelated implements ProviderEntityRelatedInterface
{
    use DecoratorBaseTrait;
    use ProviderBaseTrait;
    use DecoratorEntityRelatedTrait;
}
