<?php

namespace FHPlatform\Bundle\ConfigBundle\Config\Provider;

use FHPlatform\Bundle\ConfigBundle\Config\Decorator\Trait\DecoratorEntityRelatedTrait;
use FHPlatform\Bundle\ConfigBundle\Config\Decorator\Trait\DecoratorEntityTrait;
use FHPlatform\Bundle\ConfigBundle\Config\Decorator\Trait\DecoratorIndexTrait;
use FHPlatform\Bundle\ConfigBundle\Config\Provider\Interface\ProviderEntityInterface;
use FHPlatform\Bundle\ConfigBundle\Config\Provider\Trait\ProviderBaseTrait;
use FHPlatform\Bundle\ConfigBundle\Config\Provider\Trait\ProviderIndexTrait;
use FHPlatform\Bundle\ConfigBundle\Util\Sorter\Trait\PriorityTrait;

abstract class ProviderEntity implements ProviderEntityInterface
{
    use PriorityTrait;
    use ProviderBaseTrait;
    use ProviderIndexTrait;
    use DecoratorIndexTrait;
    use DecoratorEntityTrait;
    use DecoratorEntityRelatedTrait;
}
