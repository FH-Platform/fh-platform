<?php

namespace FHPlatform\ConfigBundle\DTO;

class Connection
{
    public function __construct(
        private readonly string $name,
        private readonly string $prefix,
        private readonly array  $elasticaConfig,
        private array           $indexes = [],
    )
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function getElasticaConfig(): array
    {
        return $this->elasticaConfig;
    }

    /** @return Index[] */
    public function getIndexes(): array
    {
        return $this->indexes;
    }

    public function setIndexes(array $indexes): void
    {
        $this->indexes = $indexes;
    }
}
