<?php

namespace FHPlatform\ConfigBundle\Config\Provider;

use FHPlatform\ConfigBundle\Config\Decorator\Trait\DecoratorEntityRelatedTrait;
use FHPlatform\ConfigBundle\Config\Decorator\Trait\DecoratorEntityTrait;
use FHPlatform\ConfigBundle\Config\Decorator\Trait\DecoratorIndexTrait;
use FHPlatform\ConfigBundle\Config\Provider\Interface\ProviderEntityInterface;
use FHPlatform\ConfigBundle\Config\Provider\Trait\ProviderBaseTrait;
use FHPlatform\ConfigBundle\Config\Provider\Trait\ProviderIndexTrait;
use FHPlatform\ConfigBundle\Util\Sorter\Trait\PriorityTrait;

abstract class ProviderEntity implements ProviderEntityInterface
{
    use PriorityTrait;
    use ProviderBaseTrait;
    use ProviderIndexTrait;
    use DecoratorIndexTrait;
    use DecoratorEntityTrait;
    use DecoratorEntityRelatedTrait;
}
