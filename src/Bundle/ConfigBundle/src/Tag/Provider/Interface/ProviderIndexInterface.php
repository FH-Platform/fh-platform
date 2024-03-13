<?php

namespace FHPlatform\ConfigBundle\Tag\Provider\Interface;

use FHPlatform\ConfigBundle\Tag\Decorator\Interface\DecoratorIndexInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('fh_platform.config.provider.index')]
interface ProviderIndexInterface extends ProviderBaseInterface, DecoratorIndexInterface
{
    public function getConnection(): string;

    public function getIndexName(string $className): string;
}
