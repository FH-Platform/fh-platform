<?php

namespace FHPlatform\Component\Persistence\Manager;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Builder\EntitiesRelatedBuilder;
use FHPlatform\Component\FrameworkBridge\EventDispatcherInterface;
use FHPlatform\Component\FrameworkBridge\MessageDispatcherInterface;
use FHPlatform\Component\Persistence\DTO\ChangedEntity;
use FHPlatform\Component\Persistence\Event\ChangedEntitiesEvent;
use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;
use FHPlatform\Component\Syncer\Message\EntitiesChangedMessage;

class EventManager
{
    public const TYPE_FLUSH = 'flush';
    public const TYPE_REQUEST_FINISHED = 'request_finished';

    private string $type = 'flush'; // TYPE_FLUSH, TYPE_REQUEST_FINISHED

    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly MessageDispatcherInterface $dispatcher,
        private readonly EntitiesRelatedBuilder $entitiesRelatedBuilder,
        private readonly PersistenceInterface $persistence,
        private readonly ConnectionsBuilder $connectionsBuilder,
    ) {
    }

    protected array $changedEntities = [];

    protected bool $transactionStarted = false;
    protected array $transactionEntities = [];

    private array $preDeleteEntities = [];

    public function eventPostCreateEntity(string $className, mixed $identifierValue): void
    {
        $this->addEntity($className, $identifierValue, ChangedEntity::TYPE_CREATE);
    }

    public function eventPostUpdateEntity(string $className, mixed $identifierValue, array $changedFields): void
    {
        $this->addEntity($className, $identifierValue, ChangedEntity::TYPE_UPDATE, $changedFields);
    }

    public function eventPostDeleteEntity(string $className, mixed $identifierValue): void
    {
        $this->addEntity($className, $identifierValue, ChangedEntity::TYPE_DELETE);
    }

    public function eventPreDeleteEntity(string $className, mixed $identifierValue): void
    {
        // TODO
        $connection = $this->connectionsBuilder->build()[0] ?? null;

        if ($connection) {
            $this->preDeleteEntities = $this->entitiesRelatedBuilder->buildForEntity($connection, $this->persistence->refreshByClassNameId($className, $identifierValue));
        }
    }

    public function eventFlush(): void
    {
        if (self::TYPE_FLUSH === $this->type) {
            $this->dispatch();
        }
    }

    public function eventRequestFinished(): void
    {
        if (self::TYPE_REQUEST_FINISHED === $this->type) {
            $this->dispatch();
        }
    }

    public function dispatch(bool $sync = false): void
    {
        if (count($this->preDeleteEntities) > 0) {
            $preDeleteEntities = $this->preDeleteEntities;
            $this->preDeleteEntities = [];
            $this->syncEntitiesManually($preDeleteEntities);
        }

        if (count($this->changedEntities) > 0) {
            $event = new ChangedEntitiesEvent($this->changedEntities);

            // TODO detect instant sync
            $this->dispatcher->dispatch(new EntitiesChangedMessage($event));
            $this->eventDispatcher->dispatch($event);

            // reset var
            $this->changedEntities = [];
        }
    }

    public function syncEntitiesManually(array $entities): void
    {
        foreach ($entities as $className => $identifierValues) {
            foreach ($identifierValues as $identifierValue) {
                $this->addEntity($className, $identifierValue, ChangedEntity::TYPE_UPDATE);
            }
        }

        $this->dispatch();
    }

    private function addEntity(string $className, mixed $identifierValue, $type, $changedFields = []): void
    {
        // make changes unique
        $hash = $className.'_'.$identifierValue;
        $changedEntity = new ChangedEntity($className, $identifierValue, $type, $changedFields);

        $this->changedEntities[$hash] = $changedEntity;

        if ($this->transactionStarted) {
            $this->transactionEntities[$className][] = $identifierValue;
        }

        // TODO when there are more updates merge changedFields, or when is delete remove all updates
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
