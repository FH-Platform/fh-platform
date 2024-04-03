<?php

namespace FHPlatform\Component\EventManager\Manager;

use FHPlatform\Component\EventManager\Event\SyncEntitiesEvent;
use FHPlatform\Component\Persistence\Event\ChangedEntityEvent;

class EventManager
{
    public const TYPE_FLUSH = 'flush';
    public const TYPE_REQUEST_FINISHED = 'request_finished';

    private string $type = self::TYPE_FLUSH;

    public function __construct(
        private readonly \Psr\EventDispatcher\EventDispatcherInterface $eventDispatcher,
    )
    {
    }

    protected array $changedEntityEvents = [];

    protected bool $changedEntityEventsTransactionStarted = false;
    protected array $changedEntityEventsTransaction = [];

    public function changedEntityEvent(ChangedEntityEvent $event): void
    {
        if (ChangedEntityEvent::TYPE_DELETE_PRE === $event->getType()) {
            $this->eventDispatcher->dispatch(new SyncEntitiesEvent([$event]));
        }

        // store changed entities for flush later, make changes unique, skip duplicated changes
        $hash = $event->getClassName(). '_' . $event->getIdentifierValue();

        $this->changedEntityEvents[$hash] = $event;

        // store transaction events if transaction is starter so that we can roll back applied changes is transaction is rollback
        if ($this->changedEntityEventsTransactionStarted) {
            $this->changedEntityEventsTransaction[$hash][] = $event;
        }
    }

    public function flushEvent(): void
    {
        if (self::TYPE_FLUSH === $this->type) {
            $this->dispatchAccumulated();
        }
    }

    public function requestFinishedEvent(): void
    {
        if (self::TYPE_REQUEST_FINISHED === $this->type) {
            $this->dispatchAccumulated();
        }
    }

    public function manualSyncAction(array $entitiesArray, bool $instant = false): void
    {
        $changedEntityEvents = [];
        foreach ($entitiesArray as $className => $identifierValues) {
            foreach ($identifierValues as $identifierValue) {
                $changedEntityEvents[] = new ChangedEntityEvent($className, $identifierValue, ChangedEntityEvent::TYPE_UPDATE);
            }
        }

        $this->dispatch($changedEntityEvents, $instant);
    }

    public function beginTransactionAction(): void
    {
        $this->changedEntityEventsTransactionStarted = true;
    }

    public function rollbackTransactionAction(): void
    {
        $this->manualSyncAction($this->changedEntityEventsTransaction);

        $this->changedEntityEventsTransactionStarted = false;
    }

    private function dispatchAccumulated(): void
    {
        //TODO detect instant sync
        $this->dispatch($this->changedEntityEvents);

        // reset var
        $this->changedEntityEvents = [];
    }

    private function dispatch($changedEntityEvents, bool $instant = false): void
    {
        if (count($changedEntityEvents) > 0) {
            $this->eventDispatcher->dispatch(new SyncEntitiesEvent($changedEntityEvents));
        }
    }
}
