<?php

namespace FHPlatform\Bundle\ConfigBundle\Config\Provider;

use FHPlatform\Bundle\ConfigBundle\Config\Decorator\Trait\DecoratorIndexTrait;
use FHPlatform\Bundle\ConfigBundle\Config\Provider\Interface\ProviderIndexInterface;
use FHPlatform\Bundle\ConfigBundle\Config\Provider\Trait\ProviderBaseTrait;
use FHPlatform\Bundle\ConfigBundle\Config\Provider\Trait\ProviderIndexTrait;
use FHPlatform\Bundle\ConfigBundle\Util\Sorter\Trait\PriorityTrait;

abstract class ProviderIndex implements ProviderIndexInterface
{
    use PriorityTrait;
    use ProviderBaseTrait;
    use ProviderIndexTrait;
    use DecoratorIndexTrait;
}
