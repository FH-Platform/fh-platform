<?php

namespace FHPlatform\ConfigBundle\Tag\Data\Provider;

use FHPlatform\ConfigBundle\Tag\Data\Decorator\Trait\DecoratorEntityRelatedTrait;
use FHPlatform\ConfigBundle\Tag\Data\Decorator\Trait\DecoratorEntityTrait;
use FHPlatform\ConfigBundle\Tag\Data\Decorator\Trait\DecoratorIndexTrait;
use FHPlatform\ConfigBundle\Tag\Data\Provider\Interface\ProviderEntityInterface;
use FHPlatform\ConfigBundle\Tag\Data\Provider\Trait\ProviderBaseTrait;

abstract class ProviderEntity implements ProviderEntityInterface
{
    use ProviderBaseTrait;
    use DecoratorIndexTrait;
    use DecoratorEntityTrait;
    use DecoratorEntityRelatedTrait;
}
