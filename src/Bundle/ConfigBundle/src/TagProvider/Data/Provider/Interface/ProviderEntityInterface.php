<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Provider\Interface;

use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Interface\DecoratorEntityInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('fh_platform.config.provider.entity')]
interface ProviderEntityInterface extends ProviderIndexInterface, ProviderEntityRelatedInterface, DecoratorEntityInterface
{
}
