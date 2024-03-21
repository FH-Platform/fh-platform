<?php

namespace FHPlatform\ConfigBundle;

use FHPlatform\ConfigBundle\Tag\Connection\ProviderConnection;
use FHPlatform\ConfigBundle\Tag\Decorator\Interface\DecoratorEntityInterface;
use FHPlatform\ConfigBundle\Tag\Decorator\Interface\DecoratorEntityRelatedInterface;
use FHPlatform\ConfigBundle\Tag\Decorator\Interface\DecoratorIndexInterface;
use FHPlatform\ConfigBundle\Tag\Provider\Interface\ProviderEntityInterface;
use FHPlatform\ConfigBundle\Tag\Provider\Interface\ProviderEntityRelatedInterface;
use FHPlatform\ConfigBundle\Tag\Provider\Interface\ProviderIndexInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ConfigBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        // connection
        $container->registerForAutoconfiguration(ProviderConnection::class)->addTag('fh_platform.config.connection');

        // provider
        $container->registerForAutoconfiguration(ProviderIndexInterface::class)->addTag('fh_platform.config.provider.index');
        $container->registerForAutoconfiguration(ProviderEntityInterface::class)->addTag('fh_platform.config.provider.entity');
        $container->registerForAutoconfiguration(ProviderEntityRelatedInterface::class)->addTag('fh_platform.config.provider.entity_related');

        // decorator
        $container->registerForAutoconfiguration(DecoratorIndexInterface::class)->addTag('fh_platform.config.decorator.index');
        $container->registerForAutoconfiguration(DecoratorEntityInterface::class)->addTag('fh_platform.config.decorator.entity');
        $container->registerForAutoconfiguration(DecoratorEntityRelatedInterface::class)->addTag('fh_platform.config.decorator.entity_related');
    }
}
