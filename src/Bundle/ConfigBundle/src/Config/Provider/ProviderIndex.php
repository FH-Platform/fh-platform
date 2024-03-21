<?php

namespace FHPlatform\ConfigBundle\Config\Provider;

use FHPlatform\ConfigBundle\Config\Decorator\Trait\DecoratorIndexTrait;
use FHPlatform\ConfigBundle\Config\Provider\Interface\ProviderIndexInterface;
use FHPlatform\ConfigBundle\Config\Provider\Trait\ProviderBaseTrait;
use FHPlatform\ConfigBundle\Config\Provider\Trait\ProviderIndexTrait;
use FHPlatform\ConfigBundle\Util\Sorter\Trait\PriorityTrait;

abstract class ProviderIndex implements ProviderIndexInterface
{
    use PriorityTrait;
    use ProviderBaseTrait;
    use ProviderIndexTrait;
    use DecoratorIndexTrait;
}
