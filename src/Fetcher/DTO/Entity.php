<?php

namespace FHPlatform\ConfigBundle\Fetcher\DTO;

class Entity
{
    public function __construct(
        private readonly mixed $entity,
        private readonly Index $index,
        private readonly array $data,
        private readonly bool $shouldBeIndexed,
    ) {
    }

    public function getEntity(): mixed
    {
        return $this->entity;
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
