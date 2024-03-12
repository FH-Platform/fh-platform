<?php

namespace FHPlatform\ConfigBundle\Tag\Data\Provider;

use FHPlatform\ConfigBundle\Service\Sorter\Trait\PriorityTrait;
use FHPlatform\ConfigBundle\Tag\Data\Decorator\Trait\DecoratorEntityRelatedTrait;
use FHPlatform\ConfigBundle\Tag\Data\Provider\Interface\ProviderEntityRelatedInterface;
use FHPlatform\ConfigBundle\Tag\Data\Provider\Trait\ProviderBaseTrait;

abstract class ProviderEntityRelated implements ProviderEntityRelatedInterface
{
    use PriorityTrait;
    use ProviderBaseTrait;
    use DecoratorEntityRelatedTrait;
}
