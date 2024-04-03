<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle\Builder;

use FHPlatform\Bundle\SymfonyBridgeBundle\Message\MessageDispatcherSymfony;
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
use FHPlatform\Component\FilterToEsDsl\Converter\ApplicatorInterface;
use FHPlatform\Component\FilterToEsDsl\Converter\FilterInterface;
use FHPlatform\Component\FilterToEsDsl\FilterQuery;
use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;
use FHPlatform\Component\SearchEngine\Manager\DataManager;
use FHPlatform\Component\SearchEngine\Manager\IndexManager;
use FHPlatform\Component\SearchEngine\Manager\QueryManager;
use FHPlatform\Component\SearchEngine\SearchEngine\SearchEngineInterface;
use FHPlatform\Component\Syncer\EventListener\SyncEntitiesEventListener;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Builder
{
    private ContainerBuilder $container;

    public function build(ContainerBuilder $container): void
    {
        $this->container = $container;

        $this->buildFramework();
        $this->buildSearchEngine();
        $this->buildMessageDispatcher();
        $this->buildConfig();
        $this->buildFilterToDsl();
    }

    public function buildFramework(): void
    {
        $container = $this->container;

        $container
            ->register(SyncEntitiesEventListener::class)
            ->setPublic(true)
            ->setAutowired(true)
            ->setAutoconfigured(true);

        $container->register(\FHPlatform\Component\EventManager\Manager\EventManager::class)->setAutowired(true)->setAutoconfigured(true)->setPublic(true);
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
        $container->register(IndexManager::class)->setAutowired(true)->setPublic(true);
        $container->register(QueryManager::class)->setAutowired(true)->setPublic(true);
        $container->register(DataManager::class)->setAutowired(true)->setPublic(true);
    }

    public function buildMessageDispatcher(): void
    {
        $container = $this->container;

        // register message handler
        // TODO
        /*$container->register(EntitiesChangedMessageHandlerSymfony::class)
            ->setAutoconfigured(true)
            ->addTag('messenger.message_handler')
            ->setArguments([
                '$entitiesChangedMessageHandler' => $container->register(EntitiesChangedMessageHandler::class)->setAutowired(true),
            ]);*/

        // register message dispatcher
        $container->register(MessageDispatcherSymfony::class)->setAutowired(true);
        // TODO
        // $container->addAliases([MessageDispatcherInterface::class => MessageDispatcherSymfony::class]);
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
        ]);

        $container->register(DocumentBuilder::class)->setPublic(true)->setArguments([
            '$configProvider' => $container->findDefinition(ConfigProvider::class),
            '$connectionsBuilder' => $container->findDefinition(ConnectionsBuilder::class),
        ]);

        $container->register(EntitiesRelatedBuilder::class)->setPublic(true)->setArguments([
            '$configProvider' => $container->findDefinition(ConfigProvider::class),
        ]);
    }

    public function buildFilterToDsl(): void
    {
        $container = $this->container;

        $container->registerForAutoconfiguration(ApplicatorInterface::class)->addTag('fh_platform.filter.applicator');
        $container->registerForAutoconfiguration(FilterInterface::class)->addTag('fh_platform.filter.filter');

        $container->register(FilterQuery::class)->setAutowired(true)->setAutoconfigured(true)->setPublic(true)
            ->setArguments([
                '$applicatorConverters' => new TaggedIteratorArgument('fh_platform.filter.applicator'),
                '$filterConverters' => new TaggedIteratorArgument('fh_platform.filter.filter'),
            ]);

        /*$container->setResources([
            //new DirectoryResource('src/Component/FilterToEsDsl/src/Converter/FilterToEsDsl/'),
            new DirectoryResource('src/Component/FilterToEsDsl/src/Converter/Applicator/'),
            //
        ])->autowire(true)->setAutoconfigured(true)->setPublic(true);*/
    }
}
