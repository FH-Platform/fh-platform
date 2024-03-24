<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\Builder;

use Doctrine\ORM\Events;
use FHPlatform\Bundle\SymfonyBridgeBundle\EventDispatcher\EventDispatcher;
use FHPlatform\Bundle\SymfonyBridgeBundle\MessageDispatcher\MessageDispatcher;
use FHPlatform\Component\Client\Provider\Data\DataClient;
use FHPlatform\Component\Client\Provider\Index\IndexClient;
use FHPlatform\Component\Client\Provider\ProviderInterface;
use FHPlatform\Component\Client\Provider\Query\QueryClient;
use FHPlatform\Component\ClientElastica\ElasticaProvider;
use FHPlatform\Component\ClientRaw\RawProvider;
use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Builder\DocumentBuilder;
use FHPlatform\Component\Config\Builder\EntitiesRelatedBuilder;
use FHPlatform\Component\Config\Builder\IndexBuilder;
use FHPlatform\Component\Config\Config\ConfigProvider;
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
use FHPlatform\Component\Persistence\Syncer\DataSyncer;
use FHPlatform\Component\PersistenceDoctrine\Listener\DoctrineListener;
use FHPlatform\Component\PersistenceDoctrine\Persistence\PersistenceDoctrine;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Builder
{
    public function build(ContainerBuilder $container): void
    {
        $this->buildMessageDispatcher($container);
        $this->buildEventDispatcher($container);

        $clientImplementation = $this->buildComponentClientElastica($container);
        $this->buildComponentClient($container, $clientImplementation);

        $persistenceImplementation = $this->buildComponentPersistenceImplementation($container);
        $this->buildComponentPersistence($container, $persistenceImplementation);

        $this->buildComponentConfig($container);
    }

    private function buildComponentPersistence(ContainerBuilder $container, string $persistenceImplementation): void
    {
        // define persistence (doctrine orm, doctrine mongodb orm, eloquent, propel, ...)

        $container->register(DataSyncer::class)->setAutowired(true)->setAutoconfigured(true)->setPublic(true);

        $container->addAliases([PersistenceInterface::class => $persistenceImplementation]);
    }

    private function buildComponentPersistenceImplementation($container): string
    {
        $container->register(DoctrineListener::class)
            ->setAutowired(true)
            ->addTag('doctrine.event_listener', ['event' => Events::postPersist]) // TODO priority
            ->addTag('doctrine.event_listener', ['event' => Events::postUpdate])
            ->addTag('doctrine.event_listener', ['event' => Events::postRemove])
            ->addTag('doctrine.event_listener', ['event' => Events::preRemove])
            ->addTag('doctrine.event_listener', ['event' => Events::postFlush]);

        $container->register(PersistenceDoctrine::class)->setAutowired(true);

        return PersistenceDoctrine::class;
    }

    private function buildComponentClient(ContainerBuilder $container, string $clientImplementation): void
    {
        // define provider (elasticsearch - elastica, elasticsearch - elasticsearch-php, algolia, solr, ...)

        $container->addAliases([ProviderInterface::class => $clientImplementation]);

        $container->register(IndexClient::class)->setAutowired(true)->setAutoconfigured(true)->setPublic(true);
        $container->register(QueryClient::class)->setAutowired(true)->setAutoconfigured(true)->setPublic(true);
        $container->register(DataClient::class)->setAutowired(true)->setAutoconfigured(true)->setPublic(true);
    }

    private function buildMessageDispatcher(ContainerBuilder $container): void
    {
        // define message dispatcher (Symfony messenger, laravel queues, ...)

        // TODO
        $container->register(EntitiesChangedMessageHandler::class)
            ->setAutoconfigured(true)
            ->setAutowired(true)
            ->addTag('messenger.message_handler');

        $container->register(EventListener::class)
            ->setAutoconfigured(true)
            ->setAutowired(true);

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

        $container->register(ConfigProvider::class)->setAutowired(true)->setArguments([
            new TaggedIteratorArgument('fh_platform.config.connection'),
            new TaggedIteratorArgument('fh_platform.config.provider.index'),
            new TaggedIteratorArgument('fh_platform.config.provider.entity'),
            new TaggedIteratorArgument('fh_platform.config.provider.entity_related'),
            new TaggedIteratorArgument('fh_platform.config.decorator.index'),
            new TaggedIteratorArgument('fh_platform.config.decorator.entity'),
            new TaggedIteratorArgument('fh_platform.config.decorator.entity_related'),
            new TaggedIteratorArgument('fh_platform.config.connection'),
        ]);

        $container->register(ConnectionsBuilder::class)->setPublic(true)->setArguments([
            '$configProvider' => $container->findDefinition(ConfigProvider::class),
        ]);

        $container->register(DocumentBuilder::class)->setPublic(true)->setArguments([
            '$configProvider' => $container->findDefinition(ConfigProvider::class),
            '$connectionsBuilder' => $container->findDefinition(ConnectionsBuilder::class),
        ]);

        $container->register(EntitiesRelatedBuilder::class)->setPublic(true)->setArguments([
            '$configProvider' => $container->findDefinition(ConfigProvider::class),
        ]);

        $container->register(IndexBuilder::class)->setPublic(true)->setArguments([
            '$configProvider' => $container->findDefinition(ConfigProvider::class),
        ]);
    }

    private function buildComponentClientElastica(ContainerBuilder $container): string
    {
        $container->register(RawProvider::class)->setAutowired(true);
        $container->register(ElasticaProvider::class)->setAutowired(true);

        if (isset($_ENV['FHPLATFORM_CLIENT_PROVIDER'])) {
            return $_ENV['FHPLATFORM_CLIENT_PROVIDER'];
        }

        return ElasticaProvider::class;
    }
}
