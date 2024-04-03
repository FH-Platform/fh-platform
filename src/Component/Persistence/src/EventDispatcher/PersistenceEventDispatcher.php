<?php

namespace FHPlatform\Component\Persistence\EventDispatcher;

use FHPlatform\Component\Persistence\Event\ChangedEntityEvent;
use FHPlatform\Component\Persistence\Event\FlushEvent;
use Psr\EventDispatcher\EventDispatcherInterface;

class PersistenceEventDispatcher
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function dispatchPostCreateEntity(string $className, mixed $identifierValue): void
    {
        $this->eventDispatcher->dispatch(new ChangedEntityEvent($className, $identifierValue, ChangedEntityEvent::TYPE_CREATE));
    }

    public function dispatchPostUpdateEntity(string $className, mixed $identifierValue, array $changedFields): void
    {
        $this->eventDispatcher->dispatch(new ChangedEntityEvent($className, $identifierValue, ChangedEntityEvent::TYPE_UPDATE, $changedFields));
    }

    public function dispatchPostDeleteEntity(string $className, mixed $identifierValue): void
    {
        $this->eventDispatcher->dispatch(new ChangedEntityEvent($className, $identifierValue, ChangedEntityEvent::TYPE_DELETE));
    }

    public function dispatchPreDeleteEntity(string $className, mixed $identifierValue): void
    {
        $this->eventDispatcher->dispatch(new ChangedEntityEvent($className, $identifierValue, ChangedEntityEvent::TYPE_DELETE_PRE));
    }

    public function dispatchFlush(): void
    {
        $this->eventDispatcher->dispatch(new FlushEvent());
    }
}
