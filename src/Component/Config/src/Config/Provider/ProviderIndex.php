<?php

namespace FHPlatform\Component\Config\Config\Provider;

use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorBaseTrait;
use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorIndexTrait;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderIndexInterface;
use FHPlatform\Component\Config\Config\Provider\Trait\ProviderBaseTrait;
use FHPlatform\Component\Config\Config\Provider\Trait\ProviderIndexTrait;

abstract class ProviderIndex implements ProviderIndexInterface
{
    use ProviderBaseTrait;
    use ProviderIndexTrait;
    use DecoratorBaseTrait;
    use DecoratorIndexTrait;
}
