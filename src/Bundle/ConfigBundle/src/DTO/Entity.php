<?php

namespace FHPlatform\ConfigBundle\DTO;

class Entity
{
    public function __construct(
        private readonly mixed $entity,
        private readonly string $className,
        private readonly mixed $identifier,
        private readonly Index $index,
        private readonly array $data,
        private readonly bool $shouldBeIndexed,
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

    public function getIdentifier(): mixed
    {
        return $this->identifier;
    }

    public function getIndex(): Index
    {
        return $this->index;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getShouldBeIndexed(): bool
    {
        return $this->shouldBeIndexed;
    }
}
