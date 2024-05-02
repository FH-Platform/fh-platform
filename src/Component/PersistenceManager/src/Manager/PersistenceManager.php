<?php

namespace FHPlatform\Component\PersistenceManager\Manager;

use FHPlatform\Component\Config\Builder\EntitiesRelatedBuilder;
use FHPlatform\Component\Persistence\Event\ChangedEntityEvent;
use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;
use FHPlatform\Component\PersistenceManager\Event\SyncEntitiesEvent;
use FHPlatform\Component\PersistenceManager\Event\SyncEntityEvent;

class PersistenceManager
{
    public function __construct(
        private readonly \Psr\EventDispatcher\EventDispatcherInterface $eventDispatcher,
        private readonly PersistenceInterface $persistence,
        private readonly EntitiesRelatedBuilder $entitiesRelatedBuilder,
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
            $entitiesRelated = $this->entitiesRelatedBuilder->build($event->getEntity());

            foreach ($entitiesRelated as $entityRelated) {
                $className = $this->persistence->getRealClassName($entityRelated::class);
                $identifierValue = $this->persistence->getIdentifierValue($entityRelated);

                $event = new SyncEntityEvent($className, $identifierValue, [], SyncEntityEvent::SOURCE_PERSISTENCE);

                $this->eventsPersistence[] = $event;
            }

            return;
        }

        $event = new SyncEntityEvent($event->getClassName(), $event->getIdentifierValue(), $event->getChangedFields(), SyncEntityEvent::SOURCE_PERSISTENCE);

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

    public function triggerChangedEntitiesAction(array $entities, bool $instant = true): void
    {
        $events = [];
        foreach ($entities as $entity) {
            $className = $this->persistence->getRealClassName($entity::class);
            $identifierValue = $this->persistence->getIdentifierValue($entity);
            $event = new SyncEntityEvent($className, $identifierValue, [], SyncEntityEvent::SOURCE_MANUALLY);

            $this->eventDispatcher->dispatch($event);
            $events[] = $event;
        }

        if ($instant) {
            $this->dispatchSyncEntitiesEvent($events);
        }
    }

    public function triggerChangedEntitiesArrayAction(array $entitiesArray, bool $instant = true): void
    {
        $events = [];
        foreach ($entitiesArray as $className => $identifierValues) {
            foreach ($identifierValues as $identifierValue) {
                $event = new SyncEntityEvent($className, $identifierValue, [], SyncEntityEvent::SOURCE_MANUALLY);

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
            $eventSycEntity = new SyncEntityEvent($event->getClassName(), $event->getIdentifierValue(), [], SyncEntityEvent::SOURCE_MANUALLY_ROLLBACK);

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
