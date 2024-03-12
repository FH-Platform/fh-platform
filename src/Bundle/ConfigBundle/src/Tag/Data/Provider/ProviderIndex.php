<?php

namespace FHPlatform\ConfigBundle\Tag\Data\Provider;

use FHPlatform\ConfigBundle\Service\Sorter\Trait\PriorityTrait;
use FHPlatform\ConfigBundle\Tag\Data\Decorator\Trait\DecoratorIndexTrait;
use FHPlatform\ConfigBundle\Tag\Data\Provider\Interface\ProviderIndexInterface;
use FHPlatform\ConfigBundle\Tag\Data\Provider\Trait\ProviderBaseTrait;
use FHPlatform\ConfigBundle\Tag\Data\Provider\Trait\ProviderIndexTrait;

abstract class ProviderIndex implements ProviderIndexInterface
{
    use PriorityTrait;
    use ProviderBaseTrait;
    use ProviderIndexTrait;
    use DecoratorIndexTrait;
}
