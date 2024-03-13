<?php

namespace FHPlatform\ConfigBundle\DTO;

class Index
{
    public function __construct(
        private readonly Connection $connection,
        private readonly string $className,
        private readonly string $name,
        private readonly array $mapping,
        private readonly array $settings,
        private readonly array $configAdditional,
    ) {
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMapping(): array
    {
        return $this->mapping;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    public function getConfigAdditional(): array
    {
        return $this->configAdditional;
    }
}
