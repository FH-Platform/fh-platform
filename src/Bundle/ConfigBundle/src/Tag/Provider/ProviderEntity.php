<?php

namespace FHPlatform\ConfigBundle\Tag\Provider;

use FHPlatform\ConfigBundle\Service\Sorter\Trait\PriorityTrait;
use FHPlatform\ConfigBundle\Tag\Decorator\Trait\DecoratorEntityRelatedTrait;
use FHPlatform\ConfigBundle\Tag\Decorator\Trait\DecoratorEntityTrait;
use FHPlatform\ConfigBundle\Tag\Decorator\Trait\DecoratorIndexTrait;
use FHPlatform\ConfigBundle\Tag\Provider\Interface\ProviderEntityInterface;
use FHPlatform\ConfigBundle\Tag\Provider\Trait\ProviderBaseTrait;
use FHPlatform\ConfigBundle\Tag\Provider\Trait\ProviderIndexTrait;

abstract class ProviderEntity implements ProviderEntityInterface
{
    use PriorityTrait;
    use ProviderBaseTrait;
    use ProviderIndexTrait;
    use DecoratorIndexTrait;
    use DecoratorEntityTrait;
    use DecoratorEntityRelatedTrait;
}
