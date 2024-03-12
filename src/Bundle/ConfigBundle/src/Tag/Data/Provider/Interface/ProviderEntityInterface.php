<?php

namespace FHPlatform\ConfigBundle\Tag\Data\Provider\Interface;

use FHPlatform\ConfigBundle\Tag\Data\Decorator\DecoratorEntityRelated;
use FHPlatform\ConfigBundle\Tag\Data\Decorator\DecoratorIndex;
use FHPlatform\ConfigBundle\Tag\Data\Decorator\Interface\DecoratorEntityInterface;
use FHPlatform\ConfigBundle\Tag\Data\Decorator\Interface\DecoratorEntityRelatedInterface;
use FHPlatform\ConfigBundle\Tag\Data\Decorator\Interface\DecoratorIndexInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('fh_platform.config.provider.entity')]
interface ProviderEntityInterface extends ProviderBaseInterface, ProviderIndexInterface, ProviderEntityRelatedInterface, DecoratorEntityInterface
{
}
