<?php

namespace FHPlatform\Component\Config\Config\Provider;

use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorEntityRelatedTrait;
use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorEntityTrait;
use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorIndexTrait;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderEntityInterface;
use FHPlatform\Component\Config\Config\Provider\Trait\ProviderBaseTrait;
use FHPlatform\Component\Config\Config\Provider\Trait\ProviderIndexTrait;

abstract class ProviderEntity implements ProviderEntityInterface
{
    use ProviderBaseTrait;
    use ProviderIndexTrait;
    use DecoratorIndexTrait;
    use DecoratorEntityTrait;
    use DecoratorEntityRelatedTrait;
}
