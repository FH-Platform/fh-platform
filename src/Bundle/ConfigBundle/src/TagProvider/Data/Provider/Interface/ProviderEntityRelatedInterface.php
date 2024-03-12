<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Provider\Interface;

use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Interface\DecoratorEntityRelatedInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('fh_platform.config.provider.entity_related')]
interface ProviderEntityRelatedInterface extends ProviderBaseInterface, DecoratorEntityRelatedInterface
{
}
