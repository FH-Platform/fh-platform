<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Provider;

use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Trait\EntityRelatedTrait;
use FHPlatform\ConfigBundle\TagProvider\Data\Provider\Interface\ProviderEntityRelatedInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('symfony_es.provider.entity_related')]
abstract class ProviderEntityRelated extends ProviderBase implements ProviderEntityRelatedInterface
{
    use EntityRelatedTrait;
}
