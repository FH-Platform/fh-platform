<?php

namespace FHPlatform\ConfigBundle\Tag\Provider\Interface;

use FHPlatform\ConfigBundle\Tag\Decorator\Interface\DecoratorEntityInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('fh_platform.config.provider.entity')]
interface ProviderEntityInterface extends ProviderBaseInterface, ProviderIndexInterface, ProviderEntityRelatedInterface, DecoratorEntityInterface
{
}
