<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Provider;

use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Trait\EntityRelatedTrait;
use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Trait\EntityTrait;
use FHPlatform\ConfigBundle\TagProvider\Data\Provider\Interface\ProviderEntityInterface;
use FHPlatform\ConfigBundle\TagProvider\Data\Provider\Interface\ProviderEntityRelatedInterface;
use FHPlatform\ConfigBundle\TagProvider\Data\Provider\Interface\ProviderIndexInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('symfony_es.provider.entity')]
#[AutoconfigureTag('symfony_es.provider.entity_related')]
abstract class ProviderEntity extends ProviderIndex implements ProviderEntityInterface, ProviderEntityRelatedInterface, ProviderIndexInterface
{
    use EntityTrait;
    use EntityRelatedTrait;
}
