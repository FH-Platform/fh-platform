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

    protected array $changedEntitiesDTO = [];

    public function flushEvent(): void
    {
        // TODO by config flush or onKernelFinishRequest

        $this->dispatch($this->changedEntitiesDTO);

        // reset var
        $this->changedEntitiesDTO = [];
    }

    public function kernelFinishRequestEvent(): void
    {
        // TODO
    }

    public function addEntity(string $className, mixed $identifierValue, $type, $changedFields, bool $dispatch): void
    {
        // make changes unique
        $hash = $className.'_'.$identifierValue;
        $changedEntityDTO = new ChangedEntityDTO($className, $identifierValue, $type, $changedFields);

        if ($dispatch) {
            $this->dispatch([$hash => $changedEntityDTO]);

            return;
        }

        $this->changedEntitiesDTO[$hash] = $changedEntityDTO;

        // TODO when there are more updates merge changedFields, or when is delete remove all updates
    }

    public function dispatch($entities): void
    {
        if (count($entities)) {
            $this->eventDispatcher->dispatch(new ChangedEntitiesEvent($entities));
        }
    }

    public function getChangedEntitiesDTO(): array
    {
        return $this->changedEntitiesDTO;
    }
}
