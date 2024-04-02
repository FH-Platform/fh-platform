<?php

namespace FHPlatform\Component\Persistence\Manager;

use FHPlatform\Component\Persistence\Event\ChangedEntity;
use FHPlatform\Component\Persistence\Event\ChangedEntityPreDelete;
use FHPlatform\Component\Persistence\Event\Flush;
use Psr\EventDispatcher\EventDispatcherInterface;

class EventManager
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function eventPostCreateEntity(string $className, mixed $identifierValue): void
    {
        $this->eventDispatcher->dispatch(new ChangedEntity($className, $identifierValue, ChangedEntity::TYPE_CREATE));
    }

    public function eventPostUpdateEntity(string $className, mixed $identifierValue, array $changedFields): void
    {
        $this->eventDispatcher->dispatch(new ChangedEntity($className, $identifierValue, ChangedEntity::TYPE_UPDATE, $changedFields));
    }

    public function eventPostDeleteEntity(string $className, mixed $identifierValue): void
    {
        $this->eventDispatcher->dispatch(new ChangedEntity($className, $identifierValue, ChangedEntity::TYPE_DELETE));
    }

    public function eventPreDeleteEntity(string $className, mixed $identifierValue): void
    {
        $this->eventDispatcher->dispatch(new ChangedEntityPreDelete($className, $identifierValue));
    }

    public function eventFlush(): void
    {
        $this->eventDispatcher->dispatch(new Flush());
    }
}
