<?php

namespace FHPlatform\ConfigBundle\DTO;

class Index
{
    public function __construct(
        private readonly Connection $connection,
        private readonly string $className,
        private readonly string $name,
        private readonly string $nameWithPrefix,
        private readonly array $configAdditional,
        private array $mapping = [],
        private array $settings = [],
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

    public function getNameWithPrefix(): string
    {
        return $this->nameWithPrefix;
    }

    public function getConfigAdditional(): array
    {
        return $this->configAdditional;
    }

    public function getMapping(): array
    {
        return $this->mapping;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    public function setMapping(array $mapping): void
    {
        $this->mapping = $mapping;
    }

    public function setSettings(array $settings): void
    {
        $this->settings = $settings;
    }
}
