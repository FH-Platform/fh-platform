<?php

namespace FHPlatform\Component\Config\DTO;

class Index
{
    public function __construct(
        private readonly Connection $connection,
        private readonly string $className,
        private readonly bool $isEntity,
        private readonly string $name,
        private readonly string $nameWithPrefix,
        private array $configAdditional = [],
        private array $mapping = [],
        private array $settings = [],
        private array $changedFields = [],
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

    public function isEntity(): bool
    {
        return $this->isEntity;
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

    public function setConfigAdditional(array $configAdditional): void
    {
        $this->configAdditional = $configAdditional;
    }

    public function setMapping(array $mapping): void
    {
        $this->mapping = $mapping;
    }

    public function setSettings(array $settings): void
    {
        $this->settings = $settings;
    }

    public function getChangedFields(): array
    {
        return $this->changedFields;
    }

    public function setChangedFields(array $changedFields): void
    {
        $this->changedFields = $changedFields;
    }
}
