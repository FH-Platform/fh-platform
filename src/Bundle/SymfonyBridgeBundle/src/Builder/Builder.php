<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\Builder;

use Doctrine\ORM\Events;
use FHPlatform\Bundle\SymfonyBridgeBundle\Event\EventDispatcherSymfony;
use FHPlatform\Bundle\SymfonyBridgeBundle\Event\EventListenerSymfony;
use FHPlatform\Bundle\SymfonyBridgeBundle\Message\MessageDispatcherSymfony;
use FHPlatform\Bundle\SymfonyBridgeBundle\Message\MessageHandlerSymfony;
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
use FHPlatform\Component\FrameworkBridge\BuilderInterface;
use FHPlatform\Component\Persistence\Event\Event\ChangedEntitiesEvent;
use FHPlatform\Component\Persistence\Event\EventDispatcher\EventDispatcherInterface;
use FHPlatform\Component\Persistence\Event\EventHelper;
use FHPlatform\Component\Persistence\Event\EventListener\EventListener;
use FHPlatform\Component\Persistence\Message\MessageDispatcher\MessageDispatcherInterface;
use FHPlatform\Component\Persistence\Message\MessageHandler\MessageHandler;
use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;
use FHPlatform\Component\Persistence\Syncer\DataSyncer;
use FHPlatform\Component\PersistenceDoctrine\Listener\DoctrineListener;
use FHPlatform\Component\PersistenceDoctrine\Persistence\PersistenceDoctrine;
use FHPlatform\Component\SearchEngine\Adapter\SearchEngineInterface;
use FHPlatform\Component\SearchEngine\Manager\DataManager;
use FHPlatform\Component\SearchEngine\Manager\IndexManager;
use FHPlatform\Component\SearchEngine\Manager\QueryManager;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Builder implements BuilderInterface
{
    private ContainerBuilder $container;

    public function build(ContainerBuilder $container): void
    {
        $this->container = $container;

        $this->buildSearchEngine();
        $this->buildPersistence();
        $this->buildMessageDispatcher();

        $this->buildEventDispatcher($container);
        $this->buildComponentConfig($container);
    }

    public function buildSearchEngine(): void
    {
        $container = $this->container;

        $searchEngine = \FHPlatform\Component\SearchEngineEs\SearchEngineEs::class;
        if (isset($_ENV['FHPLATFORM_SEARCH_ENGINE'])) {
            $searchEngine = $_ENV['FHPLATFORM_SEARCH_ENGINE'];
        }

        $container->register($searchEngine)->setAutowired(true);

        $container->addAliases([SearchEngineInterface::class => $searchEngine]);

        $container->register(IndexManager::class)->setAutowired(true)->setAutoconfigured(true)->setPublic(true);
        $container->register(QueryManager::class)->setAutowired(true)->setAutoconfigured(true)->setPublic(true);
        $container->register(DataManager::class)->setAutowired(true)->setAutoconfigured(true)->setPublic(true);
    }

    public function buildPersistence(): void
    {
        $container = $this->container;

        $persistence = PersistenceDoctrine::class;
        if (isset($_ENV['FHPLATFORM_PERSISTENCE'])) {
            $persistence = $_ENV['FHPLATFORM_PERSISTENCE'];
        }

        $container->register(DataSyncer::class)->setAutowired(true)->setAutoconfigured(true)->setPublic(true);

        $container->addAliases([PersistenceInterface::class => $persistence]);
        $container->register($persistence)->setAutowired(true);

        if (PersistenceDoctrine::class === $persistence) {
            // TODO move to bridge
            $container->register(DoctrineListener::class)
                ->setAutowired(true)
                ->addTag('doctrine.event_listener', ['event' => Events::postPersist]) // TODO priority
                ->addTag('doctrine.event_listener', ['event' => Events::postUpdate])
                ->addTag('doctrine.event_listener', ['event' => Events::postRemove])
                ->addTag('doctrine.event_listener', ['event' => Events::preRemove])
                ->addTag('doctrine.event_listener', ['event' => Events::postFlush]);
        }
    }

    public function buildMessageDispatcher(): void
    {
        $container = $this->container;

        $messageDispatcher = MessageDispatcherSymfony::class;
        if (isset($_ENV['FHPLATFORM_MESSAGE_DISPATCHER'])) {
            $messageDispatcher = $_ENV['FHPLATFORM_MESSAGE_DISPATCHER'];
        }

        // TODO
        if (MessageDispatcherSymfony::class === $messageDispatcher) {
            $container->register(MessageHandlerSymfony::class)
                ->setAutoconfigured(true)
                ->setAutowired(true)
                ->addTag('messenger.message_handler');
        }

        // register message handler
        $container->register(MessageHandler::class)->setAutowired(true);

        // register message dispatcher
        $container->register($messageDispatcher)->setAutowired(true);
        $container->addAliases([MessageDispatcherInterface::class => $messageDispatcher]);
    }

    private function buildEventDispatcher(ContainerBuilder $container): void
    {
        // define event dispatcher (Symfony events, laravel events, ...)

        if (1 === 1) {
            // TODO
            $container->register(EventListenerSymfony::class)->addTag('kernel.event_listener', [
                'event' => ChangedEntitiesEvent::class,
                'method' => 'handle',
            ])->setAutoconfigured(true)
                ->setAutowired(true);
        }

        // register event listener
        $container->register(EventListener::class)->setAutowired(true);

        // register event dispatcher
        $container->register(EventDispatcherSymfony::class)->setAutowired(true);
        $container->addAliases([EventDispatcherInterface::class => EventDispatcherSymfony::class]);

        // other
        $container->register(EventHelper::class)->setAutowired(true);
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
}
