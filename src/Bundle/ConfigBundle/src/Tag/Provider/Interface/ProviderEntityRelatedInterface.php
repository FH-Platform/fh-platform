<?php

namespace FHPlatform\ConfigBundle\Tag\Provider\Interface;

use FHPlatform\ConfigBundle\Tag\Decorator\Interface\DecoratorEntityRelatedInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('fh_platform.config.provider.entity_related')]
interface ProviderEntityRelatedInterface extends ProviderBaseInterface, DecoratorEntityRelatedInterface
{
}
