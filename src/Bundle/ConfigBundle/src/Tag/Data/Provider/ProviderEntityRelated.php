<?php

namespace FHPlatform\ConfigBundle\Tag\Data\Provider;

use FHPlatform\ConfigBundle\Tag\Data\Decorator\Trait\DecoratorEntityRelatedTrait;
use FHPlatform\ConfigBundle\Tag\Data\Provider\Interface\ProviderEntityRelatedInterface;
use FHPlatform\ConfigBundle\Tag\Data\Provider\Trait\ProviderBaseTrait;

abstract class ProviderEntityRelated implements ProviderEntityRelatedInterface
{
    use ProviderBaseTrait;
    use DecoratorEntityRelatedTrait;
}
