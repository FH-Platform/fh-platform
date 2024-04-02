<?php

namespace FHPlatform\Component\Syncer\EventListener;

use FHPlatform\Component\Persistence\Event\ChangedEntity;
use FHPlatform\Component\Persistence\Event\ChangedEntityPreDelete;
use FHPlatform\Component\Persistence\Event\Flush;
use FHPlatform\Component\PersistenceManager\Event\ChangedEntities;
use FHPlatform\Component\PersistenceManager\Event\ChangedEntitiesPreDelete;
use FHPlatform\Component\PersistenceManager\Manager\EventManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PersistenceManagerEventListener implements EventSubscriberInterface
{
    public function __construct(
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ChangedEntities::class => 'onChangedEntities',
            ChangedEntitiesPreDelete::class => 'onChangedEntitiesPreDelete',
        ];
    }

    public function onChangedEntities(ChangedEntities $event): void
    {

    }

    public function onChangedEntitiesPreDelete(ChangedEntitiesPreDelete $event): void
    {

    }
}
