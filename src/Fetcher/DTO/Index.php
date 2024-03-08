<?php

namespace FHPlatform\ConfigBundle\Fetcher\DTO;

class Index
{
    public function __construct(
        private readonly string $className,
        private readonly Connection $connection,
        private readonly string $name,
        private readonly array $mapping,
        private readonly array $settings,
    ) {
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }

    public function getMapping(): array
    {
        return $this->mapping;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }
}
