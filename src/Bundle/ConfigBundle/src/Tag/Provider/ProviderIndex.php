<?php

namespace FHPlatform\ConfigBundle\Tag\Provider;

use FHPlatform\ConfigBundle\Service\Sorter\Trait\PriorityTrait;
use FHPlatform\ConfigBundle\Tag\Decorator\Trait\DecoratorIndexTrait;
use FHPlatform\ConfigBundle\Tag\Provider\Interface\ProviderIndexInterface;
use FHPlatform\ConfigBundle\Tag\Provider\Trait\ProviderBaseTrait;
use FHPlatform\ConfigBundle\Tag\Provider\Trait\ProviderIndexTrait;

abstract class ProviderIndex implements ProviderIndexInterface
{
    use PriorityTrait;
    use ProviderBaseTrait;
    use ProviderIndexTrait;
    use DecoratorIndexTrait;
}
