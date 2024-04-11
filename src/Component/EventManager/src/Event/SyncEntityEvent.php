<?php

namespace FHPlatform\Component\EventManager\Event;

class SyncEntityEvent
{
    final public const SOURCE_PERSISTENCE = 'persistence';
    final public const SOURCE_MANUALLY = 'manually';
    final public const SOURCE_MANUALLY_ROLLBACK = 'manually_rollback';

    public function __construct(
        private readonly string $className,
        private readonly mixed $identifierValue,
        private readonly array $changedFields,
        private readonly string $source = self::SOURCE_PERSISTENCE,
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

    public function getChangedFields(): array
    {
        return $this->changedFields;
    }

    public function getSource(): string
    {
        return $this->source;
    }
}
