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

    protected array $entities = [];

    public function flushEvent(): void
    {
        // TODO by config flush or onKernelFinishRequest
        $this->dispatch();
    }

    public function kernelFinishRequestEvent(): void
    {
        // TODO
    }

    public function addEntity(string $className, mixed $identifierValue, $type, $changedFields): void
    {
        // make changes unique
        $hash = $className.'_'.$identifierValue;
        $this->entities[$hash] = new ChangedEntityDTO($className, $identifierValue, $type, $changedFields);

        // TODO when there are more updates merge changedFields, or when is delete remove all updates
    }

    public function dispatch(): void
    {
        if (count($this->entities)) {
            $this->eventDispatcher->dispatch(new ChangedEntitiesEvent($this->entities));
        }

        // reset var
        $this->entities = [];
    }

    public function dispatchEventsPreDelete(string $className, mixed $identifierValue, array $changedFields): void
    {
        $hash = $className.'_'.$identifierValue;
        $events[$hash] = new ChangedEntityDTO($className, $identifierValue, ChangedEntityDTO::TYPE_DELETE_PRE, $changedFields);

        $this->eventDispatcher->dispatch(new ChangedEntitiesEvent($events));
    }
}
