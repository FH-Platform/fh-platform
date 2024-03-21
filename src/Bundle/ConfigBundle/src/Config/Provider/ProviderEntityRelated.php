<?php

namespace FHPlatform\ConfigBundle\Config\Provider;

use FHPlatform\ConfigBundle\Config\Decorator\Trait\DecoratorEntityRelatedTrait;
use FHPlatform\ConfigBundle\Config\Provider\Interface\ProviderEntityRelatedInterface;
use FHPlatform\ConfigBundle\Config\Provider\Trait\ProviderBaseTrait;
use FHPlatform\ConfigBundle\Util\Sorter\Trait\PriorityTrait;

abstract class ProviderEntityRelated implements ProviderEntityRelatedInterface
{
    use PriorityTrait;
    use ProviderBaseTrait;
    use DecoratorEntityRelatedTrait;
}
