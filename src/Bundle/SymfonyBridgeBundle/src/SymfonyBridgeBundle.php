<?php

namespace FHPlatform\Bundle\SymfonyBridgeBundle;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use FHPlatform\Component\Config\Config\Connection\ProviderConnection;
use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorEntityInterface;
use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorEntityRelatedInterface;
use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorIndexInterface;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderEntityInterface;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderEntityRelatedInterface;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderIndexInterface;
use FHPlatform\Component\Persistence\Event\Event\ChangedEntitiesEvent;
use FHPlatform\Component\Persistence\Event\EventListener\EventListener;
use FHPlatform\Component\Persistence\Message\MessageHandler\EntitiesChangedMessageHandler;
use FHPlatform\Component\PersistenceDoctrine\Listener\DoctrineListener;
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

        $container->registerForAutoconfiguration(EventListener::class)->addTag('kernel.event_listener', [
            'event' => ChangedEntitiesEvent::class,
            'method' => 'onChangedEntities',
        ]);

        $container->registerForAutoconfiguration(EntitiesChangedMessageHandler::class)->addTag('messenger.message_handler');

        $container->registerForAutoconfiguration(DoctrineListener::class)->setAutoconfigured(true)->setPublic(true)->setAutowired(true);
        /*$container->registerForAutoconfiguration(DoctrineListener::class)->addTag(AsDoctrineListener::class, ['event' => Events::postPersist]);
        $container->registerForAutoconfiguration(DoctrineListener::class)->addTag('doctrine.orm.entity_listener', ['event' => Events::postUpdate]);
        $container->registerForAutoconfiguration(DoctrineListener::class)->addTag('doctrine.orm.entity_listener', ['event' => Events::postRemove]);
        $container->registerForAutoconfiguration(DoctrineListener::class)->addTag('doctrine.orm.entity_listener', ['event' => Events::preRemove]);
        $container->registerForAutoconfiguration(DoctrineListener::class)->addTag('doctrine.orm.entity_listener', ['event' => Events::postFlush]);*/
    }
}
