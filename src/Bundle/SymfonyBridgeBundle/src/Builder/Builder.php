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
use FHPlatform\Component\Config\Config\ConfigProvider;
use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorConnectionInterface;
use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorEntityInterface;
use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorEntityRelatedInterface;
use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorIndexInterface;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderEntityInterface;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderEntityRelatedInterface;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderIndexInterface;
use FHPlatform\Component\Config\Config\Provider\ProviderConnection;
use FHPlatform\Component\DoctrineToEs\Builder\DataBuilder;
use FHPlatform\Component\DoctrineToEs\Builder\MappingBuilder;
use FHPlatform\Component\DoctrineToEs\Builder\UpdatingMapBuilder;
use FHPlatform\Component\FilterToEsDsl\Converter\ApplicatorInterface;
use FHPlatform\Component\FilterToEsDsl\Converter\FilterInterface;
use FHPlatform\Component\FilterToEsDsl\FilterQuery;
use FHPlatform\Component\FrameworkBridge\BuilderInterface;
use FHPlatform\Component\Persistence\Event\Event\ChangedEntitiesEvent;
use FHPlatform\Component\Persistence\Event\EventDispatcher\EventDispatcherInterface;
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
use FHPlatform\Component\SearchEngineEs\Connection\ConnectionFetcher;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class Builder implements BuilderInterface
{
    private ContainerBuilder $container;

    public function build(ContainerBuilder $container): void
    {
        $this->container = $container;

        $this->buildSearchEngine();
        $this->buildPersistence();
        $this->buildMessageDispatcher();
        $this->buildEventDispatcher();
        $this->buildConfig();
        $this->buildFilter();
    }

    public function buildSearchEngine(): void
    {
        $container = $this->container;

        // fetch implementation
        $searchEngine = \FHPlatform\Component\SearchEngineEs\SearchEngineEs::class;
        if (isset($_ENV['FHPLATFORM_SEARCH_ENGINE'])) {
            $searchEngine = $_ENV['FHPLATFORM_SEARCH_ENGINE'];
        }

        // register search_engine
        $container->register($searchEngine)->setAutowired(true);
        $container->addAliases([SearchEngineInterface::class => $searchEngine]);

        // register services
        $container->register(ConnectionFetcher::class)->setAutowired(true)->setPublic(true);
        $container->register(IndexManager::class)->setAutowired(true)->setPublic(true);
        $container->register(QueryManager::class)->setAutowired(true)->setPublic(true);
        $container->register(DataManager::class)->setAutowired(true)->setPublic(true);
    }

    public function buildPersistence(): void
    {
        $container = $this->container;

        // fetch implementation
        $persistence = PersistenceDoctrine::class;
        if (isset($_ENV['FHPLATFORM_PERSISTENCE'])) {
            $persistence = $_ENV['FHPLATFORM_PERSISTENCE'];
        }

        // register persistance
        $container->register($persistence)->setAutowired(true);
        $container->addAliases([PersistenceInterface::class => $persistence]);

        // register services
        $container->register(DataSyncer::class)->setAutowired(true)->setAutoconfigured(true)->setPublic(true);

        // register each implementation
        if (PersistenceDoctrine::class === $persistence) {
            // TODO move to bridge
            $container->register(DoctrineListener::class)
                ->setAutowired(true)
                ->addTag('doctrine.event_listener', ['event' => Events::postPersist]) // TODO priority
                ->addTag('doctrine.event_listener', ['event' => Events::postUpdate])
                ->addTag('doctrine.event_listener', ['event' => Events::postRemove])
                ->addTag('doctrine.event_listener', ['event' => Events::preRemove])
                ->addTag('doctrine.event_listener', ['event' => Events::postFlush]);

            $this->buildDoctrineToEs();
        }
    }

    public function buildMessageDispatcher(): void
    {
        $container = $this->container;

        // register message handler
        $container->register(MessageHandlerSymfony::class)
            ->setAutoconfigured(true)
            ->addTag('messenger.message_handler')
            ->setArguments([
                '$messageHandler' => $container->register(MessageHandler::class)->setAutowired(true),
            ]);

        // register message dispatcher
        $container->register(MessageDispatcherSymfony::class)->setAutowired(true);
        $container->addAliases([MessageDispatcherInterface::class => MessageDispatcherSymfony::class]);
    }

    public function buildEventDispatcher(): void
    {
        $container = $this->container;

        // register event listener
        $container->register(EventListenerSymfony::class)
            ->setAutoconfigured(true)
            ->addTag('kernel.event_listener', [
                'event' => ChangedEntitiesEvent::class,
                'method' => 'handle',
            ])
            ->setArguments([
                '$eventListener' => $container->register(EventListener::class)->setAutowired(true),
            ]);

        // register event dispatcher
        $container->register(EventDispatcherSymfony::class)->setAutowired(true);
        $container->addAliases([EventDispatcherInterface::class => EventDispatcherSymfony::class]);
    }

    public function buildConfig(): void
    {
        $container = $this->container;

        // add_tag -> providers
        $container->registerForAutoconfiguration(ProviderConnection::class)->addTag('fh_platform.config.provider_connection');
        $container->registerForAutoconfiguration(ProviderIndexInterface::class)->addTag('fh_platform.config.provider.index');
        $container->registerForAutoconfiguration(ProviderEntityInterface::class)->addTag('fh_platform.config.provider.entity');
        $container->registerForAutoconfiguration(ProviderEntityRelatedInterface::class)->addTag('fh_platform.config.provider.entity_related');

        // add_tag -> decorators
        $container->registerForAutoconfiguration(DecoratorConnectionInterface::class)->addTag('fh_platform.config.decorator.connection');
        $container->registerForAutoconfiguration(DecoratorIndexInterface::class)->addTag('fh_platform.config.decorator.index');
        $container->registerForAutoconfiguration(DecoratorEntityInterface::class)->addTag('fh_platform.config.decorator.entity');
        $container->registerForAutoconfiguration(DecoratorEntityRelatedInterface::class)->addTag('fh_platform.config.decorator.entity_related');

        // set up ConfigProvider
        $container->register(ConfigProvider::class)->setAutowired(true)->setArguments([
            new TaggedIteratorArgument('fh_platform.config.provider_connection'),
            new TaggedIteratorArgument('fh_platform.config.provider.index'),
            new TaggedIteratorArgument('fh_platform.config.provider.entity'),
            new TaggedIteratorArgument('fh_platform.config.provider.entity_related'),
            new TaggedIteratorArgument('fh_platform.config.decorator.connection'),
            new TaggedIteratorArgument('fh_platform.config.decorator.index'),
            new TaggedIteratorArgument('fh_platform.config.decorator.entity'),
            new TaggedIteratorArgument('fh_platform.config.decorator.entity_related'),
            new TaggedIteratorArgument('fh_platform.config.provider_connection'),
        ]);

        // register services
        $container->register(ConnectionsBuilder::class)->setPublic(true)->setArguments([
            '$configProvider' => $container->findDefinition(ConfigProvider::class),
            '$persistence' => $container->findDefinition(PersistenceInterface::class),
        ]);

        $container->register(DocumentBuilder::class)->setPublic(true)->setArguments([
            '$configProvider' => $container->findDefinition(ConfigProvider::class),
            '$connectionsBuilder' => $container->findDefinition(ConnectionsBuilder::class),
        ]);

        $container->register(EntitiesRelatedBuilder::class)->setPublic(true)->setArguments([
            '$configProvider' => $container->findDefinition(ConfigProvider::class),
        ]);
    }

    public function buildFilter(): void
    {
        $container = $this->container;

        $container->registerForAutoconfiguration(ApplicatorInterface::class)->addTag('fh_platform.filter.applicator');
        $container->registerForAutoconfiguration(FilterInterface::class)->addTag('fh_platform.filter.filter');

        $container->register(FilterQuery::class)->setAutowired(true)->setAutoconfigured(true)->setPublic(true)
            ->setArguments([
                '$applicatorConverters' => new TaggedIteratorArgument('fh_platform.filter.applicator'),
                '$filterConverters' => new TaggedIteratorArgument('fh_platform.filter.filter'),
            ]);
    }

    public function buildDoctrineToEs(): void
    {
        $container = $this->container;

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.yaml');

        $container->register(MappingBuilder::class)->setAutowired(true)->setAutoconfigured(true)->setPublic(true);
        $container->register(DataBuilder::class)->setAutowired(true)->setAutoconfigured(true)->setPublic(true);
        $container->register(UpdatingMapBuilder::class)->setAutowired(true)->setAutoconfigured(true)->setPublic(true);
        $container->register(\FHPlatform\Component\DoctrineToEs\Builder\EntitiesRelatedBuilder::class)->setAutowired(true)->setAutoconfigured(true)->setPublic(true);
    }
}
