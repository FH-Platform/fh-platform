<?php

namespace FHPlatform\ConfigBundle\Tag\Provider;

use FHPlatform\ConfigBundle\Service\Sorter\Trait\PriorityTrait;
use FHPlatform\ConfigBundle\Tag\Decorator\Trait\DecoratorEntityRelatedTrait;
use FHPlatform\ConfigBundle\Tag\Provider\Interface\ProviderEntityRelatedInterface;
use FHPlatform\ConfigBundle\Tag\Provider\Trait\ProviderBaseTrait;

abstract class ProviderEntityRelated implements ProviderEntityRelatedInterface
{
    use PriorityTrait;
    use ProviderBaseTrait;
    use DecoratorEntityRelatedTrait;
}
