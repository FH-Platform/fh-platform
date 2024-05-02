<?php

namespace FHPlatform\Component\Config\Config\Provider;

use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorBaseTrait;
use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorEntityRelatedTrait;
use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorEntityTrait;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderEntityInterface;
use FHPlatform\Component\Config\Config\Provider\Trait\ProviderBaseTrait;

abstract class ProviderEntity extends ProviderIndex implements ProviderEntityInterface
{
    use DecoratorBaseTrait;
    use DecoratorEntityTrait;
    use DecoratorEntityRelatedTrait;
    use ProviderBaseTrait;
}
