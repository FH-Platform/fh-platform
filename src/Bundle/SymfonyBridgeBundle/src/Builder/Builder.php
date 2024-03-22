<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\Builder;

use FHPlatform\Bundle\SymfonyBridgeBundle\EventDispatcher\EventDispatcher;
use FHPlatform\Bundle\SymfonyBridgeBundle\MessageDispatcher\MessageDispatcher;
use FHPlatform\Component\Client\Provider\ProviderInterface;
use FHPlatform\Component\ClientElastica\ElasticaProvider;
use FHPlatform\Component\Config\Config\Connection\ProviderConnection;
use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorEntityInterface;
use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorEntityRelatedInterface;
use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorIndexInterface;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderEntityInterface;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderEntityRelatedInterface;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderIndexInterface;
use FHPlatform\Component\Persistence\Event\Event\ChangedEntitiesEvent;
use FHPlatform\Component\Persistence\Event\EventDispatcher\EventDispatcherInterface;
use FHPlatform\Component\Persistence\Event\EventHelper;
use FHPlatform\Component\Persistence\Event\EventListener\EventListener;
use FHPlatform\Component\Persistence\Message\MessageDispatcher\MessageDispatcherInterface;
use FHPlatform\Component\Persistence\Message\MessageHandler\EntitiesChangedMessageHandler;
use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;
use FHPlatform\Component\PersistenceDoctrine\Persistence\PersistenceDoctrine;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Builder
{
    public function build(ContainerBuilder $container): void
    {
        $this->buildPersistence($container);
        $this->buildProvider($container);
        $this->buildMessageDispatcher($container);
        $this->buildEventDispatcher($container);

        $this->buildComponentConfig($container);
    }

    private function buildPersistence(ContainerBuilder $container): void
    {
        // define persistence (doctrine orm, doctrine mongodb orm, eloquent, propel, ...)

        $container->register(PersistenceDoctrine::class)->setAutowired(true);
        $container->addAliases([PersistenceInterface::class => PersistenceDoctrine::class]);
    }

    private function buildProvider(ContainerBuilder $container): void
    {
        // define provider (elasticsearch - elastica, elasticsearch - elasticsearch-php, algolia, solr, ...)

        $container->register(ElasticaProvider::class)->setAutowired(true);
        $container->addAliases([ProviderInterface::class => ElasticaProvider::class]);
    }

    private function buildMessageDispatcher(ContainerBuilder $container): void
    {
        // define message dispatcher (Symfony messenger, laravel queues, ...)

        // TODO
        $container->registerForAutoconfiguration(EntitiesChangedMessageHandler::class)->addTag('messenger.message_handler');
        // $container->register(EventListener::class)->setAutowired(true);
        $container->register(MessageDispatcher::class)->setAutowired(true);
        $container->addAliases([MessageDispatcherInterface::class => MessageDispatcher::class]);
    }

    private function buildEventDispatcher(ContainerBuilder $container): void
    {
        // define event dispatcher (Symfony events, laravel events, ...)

        // TODO
        $container->registerForAutoconfiguration(EventListener::class)->addTag('kernel.event_listener', [
            'event' => ChangedEntitiesEvent::class,
            'method' => 'onChangedEntities',
        ]);
        $container->register(EventHelper::class)->setAutowired(true);
        $container->register(EventDispatcher::class)->setAutowired(true);
        $container->addAliases([EventDispatcherInterface::class => EventDispatcher::class]);
    }

    private function buildComponentConfig(ContainerBuilder $container): void
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
