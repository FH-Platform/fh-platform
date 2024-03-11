<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Provider;

use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Interface\IndexInterface;
use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Trait\IndexTrait;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('symfony_es.provider.index')]
abstract class ProviderIndex extends ProviderBase implements IndexInterface
{
    use IndexTrait;
}
