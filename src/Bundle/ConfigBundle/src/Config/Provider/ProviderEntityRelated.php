<?php

namespace FHPlatform\Bundle\ConfigBundle\Config\Provider;

use FHPlatform\Bundle\ConfigBundle\Config\Decorator\Trait\DecoratorEntityRelatedTrait;
use FHPlatform\Bundle\ConfigBundle\Config\Provider\Interface\ProviderEntityRelatedInterface;
use FHPlatform\Bundle\ConfigBundle\Config\Provider\Trait\ProviderBaseTrait;
use FHPlatform\Bundle\ConfigBundle\Util\Sorter\Trait\PriorityTrait;

abstract class ProviderEntityRelated implements ProviderEntityRelatedInterface
{
    use PriorityTrait;
    use ProviderBaseTrait;
    use DecoratorEntityRelatedTrait;
}
