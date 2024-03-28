<?php

namespace FHPlatform\Component\Config\DTO;

class Connection
{
    /** @param Index[] $indexes */
    public function __construct(
        private readonly string $name,
        private readonly string $prefix,
        private readonly array $configClient,
        private array $configAdditionalPreIndex = [],
        private array $configAdditionalPostIndex = [],
        private array $indexes = [],
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function getConfigClient(): array
    {
        return $this->configClient;
    }

    public function getIndexes(): array
    {
        return $this->indexes;
    }

    public function setIndexes(array $indexes): void
    {
        $this->indexes = $indexes;
    }

    public function setConfigAdditionalPreIndex(array $configAdditionalPreIndex): void
    {
        $this->configAdditionalPreIndex = $configAdditionalPreIndex;
    }

    public function getConfigAdditionalPostIndex(): array
    {
        return $this->configAdditionalPostIndex;
    }

    public function setConfigAdditionalPostIndex(array $configAdditionalPostIndex): void
    {
        $this->configAdditionalPostIndex = $configAdditionalPostIndex;
    }

    public function getConfigAdditionalPreIndex(): array
    {
        return $this->configAdditionalPreIndex;
    }
}
