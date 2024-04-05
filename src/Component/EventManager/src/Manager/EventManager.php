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
    protected array $changedEntityEvents = [];

    /** @var ChangedEntityEvent[] */
    protected array $changedEntityEventsTransaction = [];

    protected bool $changedEntityEventsTransactionStarted = false;

    public function changedEntityEvent(ChangedEntityEvent $event): void
    {
        if (ChangedEntityEvent::TYPE_DELETE_PRE === $event->getType()) {
            $this->eventDispatcher->dispatch(new SyncEntitiesEvent([$event]));
        }

        // store changed entities for flush later, make changes unique, skip duplicated changes
        $hash = $event->getClassName().'_'.$event->getIdentifierValue();

        $this->changedEntityEvents[$hash] = $event;

        // store transaction events if transaction is starter so that we can roll back applied changes is transaction is rollback
        if ($this->changedEntityEventsTransactionStarted) {
            $this->changedEntityEventsTransaction[$hash] = $event;
        }
    }

    public function flushEvent(): void
    {
        $this->dispatchAccumulated();
    }

    public function triggerEntitiesChangeAction(array $entities, bool $instant = true): void
    {
        $changedEntityEvents = [];
        foreach ($entities as $entity) {
            $className = $this->persistence->getRealClassName($entity::class);
            $identifierValue = $this->persistence->getIdentifierValue($entity);
            $changedEntityEvent = new ChangedEntityEvent($className, $identifierValue, ChangedEntityEvent::TYPE_UPDATE, [], ChangedEntityEvent::SOURCE_MANUALLY);

            // $this->eventDispatcher->dispatch($changedEntityEvent);
            $changedEntityEvents[] = $changedEntityEvent;
        }

        if ($instant) {
            $this->dispatch($changedEntityEvents);
        }
    }

    public function triggerEntitiesArrayChangeAction(array $entitiesArray, bool $instant = true): void
    {
        $changedEntityEvents = [];
        foreach ($entitiesArray as $className => $identifierValues) {
            foreach ($identifierValues as $identifierValue) {
                $changedEntityEvent = new ChangedEntityEvent($className, $identifierValue, ChangedEntityEvent::TYPE_UPDATE, [], ChangedEntityEvent::SOURCE_MANUALLY);

                // $this->eventDispatcher->dispatch($changedEntityEvent);
                $changedEntityEvents[] = $changedEntityEvent;
            }
        }

        if ($instant) {
            $this->dispatch($changedEntityEvents);
        }
    }

    public function beginTransactionAction(): void
    {
        $this->changedEntityEventsTransactionStarted = true;
    }

    public function rollbackTransactionAction(): void
    {
        $eventsUpdate = [];
        foreach ($this->changedEntityEventsTransaction as $event) {
            $changedEntityEvent = new ChangedEntityEvent($event->getClassName(), $event->getIdentifierValue(), ChangedEntityEvent::TYPE_UPDATE, [], ChangedEntityEvent::SOURCE_MANUALLY_ROLLBACK);

            // $this->eventDispatcher->dispatch($changedEntityEvent);
            $eventsUpdate[] = $changedEntityEvent;
        }

        $this->dispatch($eventsUpdate);

        $this->changedEntityEventsTransactionStarted = false;
        $this->changedEntityEventsTransaction = [];
    }

    private function dispatchAccumulated(): void
    {
        $this->dispatch($this->changedEntityEvents);

        // reset var
        $this->changedEntityEvents = [];
    }

    private function dispatch($changedEntityEvents): void
    {
        if (count($changedEntityEvents) > 0) {
            $this->eventDispatcher->dispatch(new SyncEntitiesEvent($changedEntityEvents));
        }
    }
}
