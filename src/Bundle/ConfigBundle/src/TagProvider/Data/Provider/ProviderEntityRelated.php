<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Provider;

use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Trait\DecoratorEntityRelatedTrait;
use FHPlatform\ConfigBundle\TagProvider\Data\Provider\Interface\ProviderEntityRelatedInterface;
use FHPlatform\ConfigBundle\TagProvider\Data\Provider\Trait\ProviderBaseTrait;

abstract class ProviderEntityRelated implements ProviderEntityRelatedInterface
{
    use ProviderBaseTrait;
    use DecoratorEntityRelatedTrait;
}
