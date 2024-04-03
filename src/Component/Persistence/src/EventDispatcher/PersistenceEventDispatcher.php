<?php

namespace FHPlatform\Component\Persistence\EventDispatcher;

use FHPlatform\Component\Persistence\Event\ChangedEntity;
use FHPlatform\Component\Persistence\Event\Flush;
use Psr\EventDispatcher\EventDispatcherInterface;

class PersistenceEventDispatcher
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function dispatchPostCreateEntity(string $className, mixed $identifierValue): void
    {
        $this->eventDispatcher->dispatch(new ChangedEntity($className, $identifierValue, ChangedEntity::TYPE_CREATE));
    }

    public function dispatchPostUpdateEntity(string $className, mixed $identifierValue, array $changedFields): void
    {
        $this->eventDispatcher->dispatch(new ChangedEntity($className, $identifierValue, ChangedEntity::TYPE_UPDATE, $changedFields));
    }

    public function dispatchPostDeleteEntity(string $className, mixed $identifierValue): void
    {
        $this->eventDispatcher->dispatch(new ChangedEntity($className, $identifierValue, ChangedEntity::TYPE_DELETE));
    }

    public function dispatchPreDeleteEntity(string $className, mixed $identifierValue): void
    {
        $this->eventDispatcher->dispatch(new ChangedEntity($className, $identifierValue, ChangedEntity::TYPE_DELETE_PRE));
    }

    public function dispatchFlush(): void
    {
        $this->eventDispatcher->dispatch(new Flush());
    }
}
