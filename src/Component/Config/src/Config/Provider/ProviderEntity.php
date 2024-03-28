<?php

namespace FHPlatform\Component\Config\Config\Provider;

use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorEntityRelatedTrait;
use FHPlatform\Component\Config\Config\Decorator\Trait\DecoratorEntityTrait;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderEntityInterface;

abstract class ProviderEntity extends ProviderIndex implements ProviderEntityInterface
{
    use DecoratorEntityTrait;
    use DecoratorEntityRelatedTrait;
}
