<?php

namespace FHPlatform\Component\Persistence\Event;

class ChangedEntityPreDelete
{
    public function __construct(
        private readonly string $className,
        private readonly mixed $identifier,
    ) {
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getIdentifierValue(): mixed
    {
        return $this->identifier;
    }
}
