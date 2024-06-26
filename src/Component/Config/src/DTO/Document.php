<?php

namespace FHPlatform\Component\Config\DTO;

class Document
{
    final public const TYPE_UPSERT = 'upsert';
    final public const TYPE_DELETE = 'delete';

    public function __construct(
        private readonly Index $index,
        private readonly mixed $identifierValue,
        private readonly array $data,
        private readonly string $type,
    ) {
    }

    public function getIndex(): Index
    {
        return $this->index;
    }

    public function getIdentifierValue(): mixed
    {
        return $this->identifierValue;
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
