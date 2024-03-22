<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\Builder;

use FHPlatform\Bundle\SymfonyBridgeBundle\EventDispatcher\EventDispatcher;
use FHPlatform\Bundle\SymfonyBridgeBundle\MessageDispatcher\MessageDispatcher;
use FHPlatform\Component\Client\Provider\ProviderInterface;
use FHPlatform\Component\ClientElastica\ElasticaProvider;
use FHPlatform\Component\Persistence\Event\EventDispatcher\EventDispatcherInterface;
use FHPlatform\Component\Persistence\Event\EventHelper;
use FHPlatform\Component\Persistence\Message\MessageDispatcher\MessageDispatcherInterface;
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

        $container->register(MessageDispatcher::class)->setAutowired(true);
        $container->addAliases([MessageDispatcherInterface::class => MessageDispatcher::class]);
    }

    private function buildEventDispatcher(ContainerBuilder $container): void
    {
        // define event dispatcher (Symfony events, laravel events, ...)

        //TODO
        $container->register(EventHelper::class)->setAutowired(true);
        $container->register(EventDispatcher::class)->setAutowired(true);
        $container->addAliases([EventDispatcherInterface::class => EventDispatcher::class]);
    }
}
