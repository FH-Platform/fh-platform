<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle;

use FHPlatform\Bundle\ConfigBundle\Config\Connection\ProviderConnection;
use FHPlatform\Bundle\ConfigBundle\Config\Decorator\Interface\DecoratorEntityInterface;
use FHPlatform\Bundle\ConfigBundle\Config\Decorator\Interface\DecoratorEntityRelatedInterface;
use FHPlatform\Bundle\ConfigBundle\Config\Decorator\Interface\DecoratorIndexInterface;
use FHPlatform\Bundle\ConfigBundle\Config\Provider\Interface\ProviderEntityInterface;
use FHPlatform\Bundle\ConfigBundle\Config\Provider\Interface\ProviderEntityRelatedInterface;
use FHPlatform\Bundle\ConfigBundle\Config\Provider\Interface\ProviderIndexInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SymfonyBridgeBundle extends Bundle
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
