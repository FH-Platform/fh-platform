<?php

namespace FHPlatform\Component\EventManager\Manager;

use FHPlatform\Component\EventManager\Event\SyncEntitiesEvent;
use FHPlatform\Component\Persistence\Event\ChangedEntityEvent;
use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;

class EventManager
{
    public function __construct(
        private readonly \Psr\EventDispatcher\EventDispatcherInterface $eventDispatcher,
        private readonly PersistenceInterface $persistence,
    ) {
    }

    /** @var ChangedEntityEvent[] */
    protected array $eventsPersistence = [];

    /** @var ChangedEntityEvent[] */
    protected array $changedEntityEventsTransaction = [];

    protected bool $changedEntityEventsTransactionStarted = false;

    public function changedEntityEvent(ChangedEntityEvent $event): void
    {
        // handle event only for persistence source
        if (ChangedEntityEvent::SOURCE_PERSISTENCE !== $event->getSource()) {
            return;
        }

        if (ChangedEntityEvent::TYPE_DELETE_PRE === $event->getType()) {
            $this->dispatchSyncEntitiesEvent([$event]);

            return;
        }

        // store changed entities for flush later, make changes unique, skip duplicated changes
        //TODO
        //$hash = $event->getClassName().'_'.$event->getIdentifierValue();

        $this->eventsPersistence[] = $event;

        // store transaction events if transaction is starter so that we can roll back applied changes is transaction is rollback
        if ($this->changedEntityEventsTransactionStarted) {
            $this->changedEntityEventsTransaction[] = $event;
        }
    }

    public function flushEvent(): void
    {
        $this->dispatchSyncEntitiesEvent($this->eventsPersistence);

        // reset var
        $this->eventsPersistence = [];
    }

    public function triggerEntitiesChangeAction(array $entities, bool $instant = true): void
    {
        $events = [];
        foreach ($entities as $entity) {
            $className = $this->persistence->getRealClassName($entity::class);
            $identifierValue = $this->persistence->getIdentifierValue($entity);
            $changedEntityEvent = new ChangedEntityEvent($className, $identifierValue, ChangedEntityEvent::TYPE_UPDATE, [], ChangedEntityEvent::SOURCE_MANUALLY);

            $this->eventDispatcher->dispatch($changedEntityEvent);
            $events[] = $changedEntityEvent;
        }

        if ($instant) {
            $this->dispatchSyncEntitiesEvent($events);
        }
    }

    public function triggerEntitiesArrayChangeAction(array $entitiesArray, bool $instant = true): void
    {
        $events = [];
        foreach ($entitiesArray as $className => $identifierValues) {
            foreach ($identifierValues as $identifierValue) {
                $changedEntityEvent = new ChangedEntityEvent($className, $identifierValue, ChangedEntityEvent::TYPE_UPDATE, [], ChangedEntityEvent::SOURCE_MANUALLY);

                $this->eventDispatcher->dispatch($changedEntityEvent);
                $events[] = $changedEntityEvent;
            }
        }

        if ($instant) {
            $this->dispatchSyncEntitiesEvent($events);
        }
    }

    public function beginTransactionAction(): void
    {
        $this->changedEntityEventsTransactionStarted = true;
    }

    public function rollbackTransactionAction(bool $instant = true): void
    {
        $events = [];
        foreach ($this->changedEntityEventsTransaction as $event) {
            $changedEntityEvent = new ChangedEntityEvent($event->getClassName(), $event->getIdentifierValue(), ChangedEntityEvent::TYPE_UPDATE, [], ChangedEntityEvent::SOURCE_MANUALLY_ROLLBACK);

            $this->eventDispatcher->dispatch($changedEntityEvent);
            $events[] = $changedEntityEvent;
        }

        if ($instant) {
            $this->dispatchSyncEntitiesEvent($events);
        }

        $this->changedEntityEventsTransactionStarted = false;
        $this->changedEntityEventsTransaction = [];
    }

    private function dispatchSyncEntitiesEvent($changedEntityEvents): void
    {
        if (count($changedEntityEvents) > 0) {
            $this->eventDispatcher->dispatch(new SyncEntitiesEvent($changedEntityEvents));
        }
    }
}
