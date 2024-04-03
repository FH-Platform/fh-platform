<?php

namespace FHPlatform\Component\EventManager\Manager;

use FHPlatform\Component\EventManager\Event\ChangedEntities;
use FHPlatform\Component\Persistence\Event\ChangedEntity;

class EventManager
{
    public const TYPE_FLUSH = 'flush';
    public const TYPE_REQUEST_FINISHED = 'request_finished';

    private string $type = self::TYPE_FLUSH;

    public function __construct(
        private readonly \Psr\EventDispatcher\EventDispatcherInterface $eventDispatcher,
    ) {
    }

    protected array $changedEntities = [];

    protected bool $transactionStarted = false;
    protected array $transactionEntities = [];

    public function changedEntityEvent(ChangedEntity $event): void
    {
        if($event->getType() === ChangedEntity::TYPE_DELETE_PRE){
            $this->eventDispatcher->dispatch(new ChangedEntities([$event]));
        }

        // store changed entities for flush later, make changes unique, skip duplicated changes
        $hash = $event->getClassName().'_'.$event->getIdentifierValue();
        $this->changedEntities[$hash] = $event;

        // store transaction events if transaction is starter so that we can roll back applied changes is transaction is rollback
        if ($this->transactionStarted) {
            $this->transactionEntities[$event->getClassName()][] = $event->getIdentifierValue();
        }
    }

    public function flushEvent(): void
    {
        // event is triggered by
        if (self::TYPE_FLUSH === $this->type) {
            $this->dispatch();
        }
    }

    public function requestFinishedEvent(): void
    {
        if (self::TYPE_REQUEST_FINISHED === $this->type) {
            $this->dispatch();
        }
    }

    public function manualSyncAction(array $entities): void
    {
        foreach ($entities as $className => $identifierValues) {
            foreach ($identifierValues as $identifierValue) {
                $this->changedEntityEvent(new ChangedEntity($className, $identifierValue, ChangedEntity::TYPE_UPDATE));
            }
        }

        $this->dispatch();
    }

    public function beginTransactionAction(): void
    {
        $this->transactionStarted = true;
    }

    public function rollbackTransactionAction(): void
    {
        $this->manualSyncAction($this->transactionEntities);
    }

    private function dispatch(bool $sync = false): void
    {
        if (count($this->changedEntities) > 0) {
            $this->eventDispatcher->dispatch(new ChangedEntities($this->changedEntities));

            // reset var
            $this->changedEntities = [];
        }
    }
}
