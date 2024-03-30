<?php

namespace FHPlatform\Component\Persistence\Event;

use FHPlatform\Component\FrameworkBridge\EventDispatcherInterface;
use FHPlatform\Component\Persistence\DTO\ChangedEntity;

class EventManager
{
    public const TYPE_FLUSH = 'flush';
    public const TYPE_REQUEST_FINISHED = 'request_finished';

    private string $type = 'flush'; // TYPE_FLUSH, TYPE_REQUEST_FINISHED

    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    protected array $changedEntities = [];

    public function eventPostCreate(string $className, mixed $identifierValue): void
    {
        $this->addEntity($className, $identifierValue, ChangedEntity::TYPE_CREATE);
    }

    public function eventPostUpdate(string $className, mixed $identifierValue, array $changedFields): void
    {
        $this->addEntity($className, $identifierValue, ChangedEntity::TYPE_UPDATE, $changedFields);
    }

    public function eventPostDelete(string $className, mixed $identifierValue): void
    {
        $this->addEntity($className, $identifierValue, ChangedEntity::TYPE_DELETE);
    }

    public function eventPreDelete(string $className, mixed $identifierValue): void
    {
        $this->addEntity($className, $identifierValue, ChangedEntity::TYPE_DELETE_PRE);
        $this->eventFlush();
    }

    public function addEntity(string $className, mixed $identifierValue, $type, $changedFields = []): void
    {
        // make changes unique
        $hash = $className.'_'.$identifierValue;
        $changedEntity = new ChangedEntity($className, $identifierValue, $type, $changedFields);

        $this->changedEntities[$hash] = $changedEntity;

        // TODO when there are more updates merge changedFields, or when is delete remove all updates
    }

    public function eventFlush(): void
    {
        if (self::TYPE_FLUSH === $this->type) {
            $this->dispatch();
        }
    }

    public function eventRequestFinished(): void
    {
        // TODO
        if (self::TYPE_REQUEST_FINISHED === $this->type) {
            $this->dispatch();
        }
    }

    public function dispatch(): void
    {
        if (count($this->changedEntities)) {
            $this->eventDispatcher->dispatch(new ChangedEntitiesEvent($this->changedEntities));

            // reset var
            $this->changedEntities = [];
        }
    }
}
