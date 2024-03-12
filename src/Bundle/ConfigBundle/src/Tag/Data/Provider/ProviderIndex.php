<?php

namespace FHPlatform\ConfigBundle\Tag\Data\Provider;

use FHPlatform\ConfigBundle\Service\Sorter\Trait\PriorityTrait;
use FHPlatform\ConfigBundle\Tag\Data\Decorator\Trait\DecoratorIndexTrait;
use FHPlatform\ConfigBundle\Tag\Data\Provider\Interface\ProviderIndexInterface;
use FHPlatform\ConfigBundle\Tag\Data\Provider\Trait\ProviderBaseTrait;

abstract class ProviderIndex implements ProviderIndexInterface
{
    use PriorityTrait;
    use ProviderBaseTrait;
    use DecoratorIndexTrait;
}
