<?php

namespace FHPlatform\Component\FilterToEsDsl\Query\DTO;

class ResultDto
{
    public function __construct(
        private readonly array $meta,
        private readonly array $items,
    ) {
    }

    public function getMeta(): array
    {
        return $this->meta;
    }

    public function getItems(): array
    {
        return $this->items;
    }
}
