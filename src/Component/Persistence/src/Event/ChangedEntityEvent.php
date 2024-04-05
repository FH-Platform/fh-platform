<?php

namespace FHPlatform\Component\Persistence\Event;

class ChangedEntityEvent
{
    final public const TYPE_CREATE = 'create';
    final public const TYPE_UPDATE = 'update';
    final public const TYPE_DELETE = 'delete';
    final public const TYPE_DELETE_PRE = 'delete_pre';

    final public const TRIGGERED_PERSISTENCE = 'persistence';
    final public const TRIGGERED_PERSISTENCE_ROLLBACK = 'persistence_rollback';
    final public const TRIGGERED_MANUALLY = 'manually';

    public function __construct(
        private readonly string $className,
        private readonly mixed $identifierValue,
        private readonly string $type,
        private readonly array $changedFields = [],
        private readonly string $triggered = self::TRIGGERED_PERSISTENCE,
    ) {
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getIdentifierValue(): mixed
    {
        return $this->identifierValue;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getChangedFields(): array
    {
        return $this->changedFields;
    }

    public function getTriggered(): string
    {
        return $this->triggered;
    }
}
