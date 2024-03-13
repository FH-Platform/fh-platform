<?php

namespace FHPlatform\ConfigBundle\DTO;

class Connection
{
    /** @param Index[] $indexes */
    public function __construct(
        private readonly string $name,
        private readonly string $prefix,
        private readonly array $clientConfig,
        private readonly array $additionalConfig = [],
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

    public function getClientConfig(): array
    {
        return $this->clientConfig;
    }

    public function getIndexes(): array
    {
        return $this->indexes;
    }

    public function setIndexes(array $indexes): void
    {
        $this->indexes = $indexes;
    }

    public function getAdditionalConfig(): array
    {
        return $this->additionalConfig;
    }
}
