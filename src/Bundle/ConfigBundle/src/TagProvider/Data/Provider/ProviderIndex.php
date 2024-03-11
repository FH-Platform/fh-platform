<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Provider;

use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Trait\DecoratorIndexTrait;
use FHPlatform\ConfigBundle\TagProvider\Data\Provider\Interface\ProviderIndexInterface;
use FHPlatform\ConfigBundle\TagProvider\Data\Provider\Trait\ProviderBaseTrait;

abstract class ProviderIndex implements ProviderIndexInterface
{
    use ProviderBaseTrait;
    use DecoratorIndexTrait;
}
