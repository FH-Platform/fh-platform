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

    public function changedEntity(ChangedEntity $event): void
    {
        // store changed entities for flush later, make changes unique, skip duplicated changes
        $hash = $event->getClassName().'_'.$event->getIdentifierValue();
        $this->changedEntities[$hash] = $event;

        // store transaction events if transaction is starter so that we can roll back applied changes is transaction is rollback
        if ($this->transactionStarted) {
            $this->transactionEntities[$event->getClassName()][] = $event->getIdentifierValue();
        }
    }

    public function flush(): void
    {
        // event is triggered by
        if (self::TYPE_FLUSH === $this->type) {
            $this->dispatch();
        }
    }

    public function requestFinished(): void
    {
        if (self::TYPE_REQUEST_FINISHED === $this->type) {
            $this->dispatch();
        }
    }

    public function dispatch(bool $sync = false): void
    {
        if (count($this->changedEntities) > 0) {
            $this->eventDispatcher->dispatch(new ChangedEntities($this->changedEntities));

            // reset var
            $this->changedEntities = [];
        }
    }

    public function syncEntitiesManually(array $entities): void
    {
        foreach ($entities as $className => $identifierValues) {
            foreach ($identifierValues as $identifierValue) {
                $this->changedEntity(new ChangedEntity($className, $identifierValue, ChangedEntity::TYPE_UPDATE));
            }
        }

        $this->dispatch();
    }

    public function beginTransaction(): void
    {
        $this->transactionStarted = true;
    }

    public function rollBack(): void
    {
        $this->syncEntitiesManually($this->transactionEntities);
    }
}
