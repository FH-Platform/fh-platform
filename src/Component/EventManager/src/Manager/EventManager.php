<?php

namespace FHPlatform\Component\EventManager\Manager;

use FHPlatform\Component\EventManager\Event\SyncEntitiesEvent;
use FHPlatform\Component\EventManager\Event\SyncEntityEvent;
use FHPlatform\Component\Persistence\Event\ChangedEntityEvent;
use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;

class EventManager
{
    public function __construct(
        private readonly \Psr\EventDispatcher\EventDispatcherInterface $eventDispatcher,
        private readonly PersistenceInterface $persistence,
    ) {
    }

    /** @var SyncEntityEvent[] */
    protected array $eventsPersistence = [];

    /** @var SyncEntityEvent[] */
    protected array $eventsTransaction = [];

    protected bool $transactionStarted = false;

    public function changedEntityEvent(ChangedEntityEvent $event): void
    {
        // handle event only (create, update, delete), not delete_pre
        if (ChangedEntityEvent::TYPE_DELETE_PRE === $event->getType()) {
            return;
        }

        // store changed entities for flush later, make changes unique, skip duplicated changes
        // TODO
        // $hash = $event->getClassName().'_'.$event->getIdentifierValue();

        $event = new SyncEntityEvent($event->getClassName(), $event->getIdentifierValue(), SyncEntityEvent::SOURCE_PERSISTENCE);

        $this->eventsPersistence[] = $event;

        // store transaction events if transaction is starter so that we can roll back applied changes is transaction is rollback
        if ($this->transactionStarted) {
            $this->eventsTransaction[] = $event;
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
            $event = new SyncEntityEvent($className, $identifierValue, SyncEntityEvent::SOURCE_MANUALLY);

            $this->eventDispatcher->dispatch($event);
            $events[] = $event;
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
                $event = new SyncEntityEvent($className, $identifierValue, SyncEntityEvent::SOURCE_MANUALLY);

                $this->eventDispatcher->dispatch($event);
                $events[] = $event;
            }
        }

        if ($instant) {
            $this->dispatchSyncEntitiesEvent($events);
        }
    }

    public function beginTransactionAction(): void
    {
        $this->transactionStarted = true;
    }

    public function rollbackTransactionAction(bool $instant = true): void
    {
        $events = [];
        foreach ($this->eventsTransaction as $event) {
            $eventSycEntity = new SyncEntityEvent($event->getClassName(), $event->getIdentifierValue(), SyncEntityEvent::SOURCE_MANUALLY_ROLLBACK);

            $this->eventDispatcher->dispatch($eventSycEntity);
            $events[] = $event;
        }

        if ($instant) {
            $this->dispatchSyncEntitiesEvent($events);
        }

        $this->transactionStarted = false;
        $this->eventsTransaction = [];
    }

    private function dispatchSyncEntitiesEvent($events): void
    {
        if (count($events) > 0) {
            $this->eventDispatcher->dispatch(new SyncEntitiesEvent($events));
        }
    }
}
