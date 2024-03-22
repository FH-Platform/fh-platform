<?php

namespace FHPlatform\Bundle\PersistenceDoctrineBundle\EventDispatcher;

use FHPlatform\Component\Persistence\DTO\ChangedEntityDTO;
use FHPlatform\Component\Persistence\Event\Event\ChangedEntitiesEvent;
use Psr\EventDispatcher\EventDispatcherInterface;

class EventDispatcher
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    protected array $events = [];

    public function flushEvent(): void
    {
        // TODO by config flush or onKernelFinishRequest
        $this->dispatchEvents();
    }

    public function kernelFinishRequestEvent(): void
    {
        // TODO
    }

    public function addEvent(string $className, mixed $identifierValue, $type, $changedFields): void
    {
        // make changes unique
        $hash = $className.'_'.$identifierValue;
        $this->events[$hash] = new ChangedEntityDTO($className, $identifierValue, $type, $changedFields);

        // TODO when there are more updates merge changedFields, or when is delete remove all updates
    }

    public function dispatchEvents(): void
    {
        if (count($this->events)) {
            $this->eventDispatcher->dispatch(new ChangedEntitiesEvent($this->events));
        }

        foreach ($this->events as $event) {
            $this->eventDispatcher->dispatch($event);
        }

        // reset var
        $this->events = [];
    }

    public function dispatchEventsPreDelete(string $className, mixed $identifierValue, array $changedFields): void
    {
        $hash = $className.'_'.$identifierValue;
        $events[$hash] = new ChangedEntityDTO($className, $identifierValue, ChangedEntityDTO::TYPE_DELETE_PRE, $changedFields);

        $this->eventDispatcher->dispatch(new ChangedEntitiesEvent($events));
    }
}
