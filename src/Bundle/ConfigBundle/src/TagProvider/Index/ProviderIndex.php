<?php

namespace FHPlatform\ConfigBundle\TagProvider\Index;

use FHPlatform\ConfigBundle\TagProvider\Decorator\Interface\IndexInterface;
use FHPlatform\ConfigBundle\TagProvider\Decorator\Trait\IndexTrait;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('symfony_es.provider.index')]
abstract class ProviderIndex extends ProviderBase implements IndexInterface
{
    use IndexTrait;
}
