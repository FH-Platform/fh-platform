<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Provider;

use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Trait\DecoratorEntityRelatedTrait;
use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Trait\DecoratorEntityTrait;
use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Trait\DecoratorIndexTrait;
use FHPlatform\ConfigBundle\TagProvider\Data\Provider\Interface\ProviderEntityInterface;
use FHPlatform\ConfigBundle\TagProvider\Data\Provider\Trait\ProviderBaseTrait;

abstract class ProviderEntity implements ProviderEntityInterface
{
    use ProviderBaseTrait;
    use DecoratorIndexTrait;
    use DecoratorEntityTrait;
    use DecoratorEntityRelatedTrait;
}
