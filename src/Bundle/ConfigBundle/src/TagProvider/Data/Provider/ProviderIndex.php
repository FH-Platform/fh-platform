<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Provider;

use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Trait\IndexTrait;
use FHPlatform\ConfigBundle\TagProvider\Data\Provider\Interface\ProviderIndexInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('symfony_es.provider.index')]
abstract class ProviderIndex extends ProviderBase implements ProviderIndexInterface
{
    use IndexTrait;
}
