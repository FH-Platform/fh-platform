<?php

namespace FHPlatform\ConfigBundle\Tag\Data\Provider\Interface;

use FHPlatform\ConfigBundle\Tag\Data\Decorator\Interface\DecoratorEntityRelatedInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('fh_platform.config.provider.entity_related')]
interface ProviderEntityRelatedInterface extends ProviderBaseInterface, DecoratorEntityRelatedInterface
{
}
