<?php

namespace FHPlatform\Component\EventManager\Event;

class PrepareEntityEvent
{
    public function __construct(
        private readonly string $className,
        private readonly mixed $identifierValue,
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
}
