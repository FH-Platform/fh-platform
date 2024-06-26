<?php

namespace FHPlatform\Component\Persistence\Event;

class ChangedEntityEvent
{
    final public const TYPE_CREATE = 'create';
    final public const TYPE_UPDATE = 'update';
    final public const TYPE_DELETE = 'delete';
    final public const TYPE_DELETE_PRE = 'delete_pre';

    public function __construct(
        private readonly mixed $entity,
        private readonly string $className,
        private readonly mixed $identifierValue,
        private readonly string $type,
        private readonly array $changedFields = [],
    ) {
    }

    public function getEntity(): mixed
    {
        return $this->entity;
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
}
