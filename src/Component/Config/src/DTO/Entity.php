<?php

namespace FHPlatform\Component\Config\DTO;

class Entity
{
    public function __construct(
        private readonly Index $index,
        private readonly mixed $identifier,
        private readonly array $data,
        private readonly string $type,
    ) {
    }

    public function getIndex(): Index
    {
        return $this->index;
    }

    public function getIdentifier(): mixed
    {
        return $this->identifier;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
