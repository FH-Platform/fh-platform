<?php

namespace FHPlatform\Bundle\EventManagerBundle\Builder;

use Doctrine\ORM\Events;
use FHPlatform\Bundle\EventManagerBundle\EventListener\PersistenceEventListener;
use FHPlatform\Component\DoctrineToEs\Builder\DataBuilder;
use FHPlatform\Component\DoctrineToEs\Builder\EntitiesRelatedBuilder;
use FHPlatform\Component\DoctrineToEs\Builder\MappingBuilder;
use FHPlatform\Component\DoctrineToEs\Builder\UpdatingMapBuilder;
use FHPlatform\Component\Persistence\EventDispatcher\PersistenceEventDispatcher;
use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;
use FHPlatform\Component\PersistenceDoctrine\DoctrinePersistence;
use FHPlatform\Component\PersistenceDoctrine\DoctrinePersistenceListener;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class EventManagerBuilder
{
    private ContainerBuilder $container;

    public function build(ContainerBuilder $container): void
    {
        $this->container = $container;

        $this->buildPersistence();

        // TODO
        $container
            ->register(PersistenceEventListener::class)
            ->setPublic(true)
            ->setAutowired(true)
            ->setAutoconfigured(true);
    }

    public function buildPersistence(): void
    {
        $container = $this->container;

        // fetch implementation
        $persistence = DoctrinePersistence::class;
        if (isset($_ENV['FHPLATFORM_PERSISTENCE'])) {
            $persistence = $_ENV['FHPLATFORM_PERSISTENCE'];
        }

        // register persistence
        $container->register($persistence)->setAutowired(true);
        $container->addAliases([PersistenceInterface::class => $persistence]);

        $container->register(PersistenceEventDispatcher::class)->setAutowired(true)->setAutoconfigured(true)->setPublic(true);

        // register doctrine listener
        if (DoctrinePersistence::class === $persistence) {
            $container->register(DoctrinePersistenceListener::class)
                ->setAutowired(true)
                ->addTag('doctrine.event_listener', ['event' => Events::postPersist]) // TODO priority
                ->addTag('doctrine.event_listener', ['event' => Events::postUpdate])
                ->addTag('doctrine.event_listener', ['event' => Events::postRemove])
                ->addTag('doctrine.event_listener', ['event' => Events::preRemove])
                ->addTag('doctrine.event_listener', ['event' => Events::postFlush])
                ->addTag('doctrine.event_listener', ['event' => \Doctrine\DBAL\Events::onTransactionBegin])
                ->addTag('doctrine.event_listener', ['event' => \Doctrine\DBAL\Events::onTransactionRollBack]);

            $this->buildDoctrineToEs();
        }
    }

    public function buildDoctrineToEs(): void
    {
        // TODO move to ...
        $container = $this->container;

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.yaml');

        $container->register(MappingBuilder::class)->setAutowired(true)->setAutoconfigured(true)->setPublic(true);
        $container->register(DataBuilder::class)->setAutowired(true)->setAutoconfigured(true)->setPublic(true);
        $container->register(UpdatingMapBuilder::class)->setAutowired(true)->setAutoconfigured(true)->setPublic(true);
        $container->register(EntitiesRelatedBuilder::class)->setAutowired(true)->setAutoconfigured(true)->setPublic(true);
    }
}
