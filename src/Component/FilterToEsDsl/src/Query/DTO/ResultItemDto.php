<?php

namespace FHPlatform\Component\FilterToEsDsl\Query\DTO;

class ResultItemDto
{
    public function __construct(
        private readonly array $meta,
        private readonly mixed $entity,
    ) {
    }

    public function getMeta(): array
    {
        return $this->meta;
    }

    public function getEntity(): mixed
    {
        return $this->entity;
    }
}
