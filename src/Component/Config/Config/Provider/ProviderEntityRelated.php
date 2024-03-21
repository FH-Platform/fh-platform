<?php

namespace FHPlatform\Component\Config\Config\Provider;

use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorEntityRelatedTrait;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderEntityRelatedInterface;
use FHPlatform\Component\Config\Config\Provider\Trait\ProviderBaseTrait;
use FHPlatform\Component\Config\Util\Sorter\Trait\PriorityTrait;

abstract class ProviderEntityRelated implements ProviderEntityRelatedInterface
{
    use PriorityTrait;
    use ProviderBaseTrait;
    use DecoratorEntityRelatedTrait;
}
