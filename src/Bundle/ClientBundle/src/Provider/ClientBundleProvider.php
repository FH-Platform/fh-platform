<?php

namespace FHPlatform\ClientBundle\Provider;

use FHPlatform\ClientBundle\Exception\ClassNameForIndexNotExists;
use FHPlatform\ConfigBundle\DTO\Index;

class ClientBundleProvider
{
    private array $connections = [];
    private array $indexes = [];

    public function getConnections(): array
    {
        return $this->connections;
    }

    public function setConnections(array $connections): void
    {
        $this->connections = $connections;
    }

    public function getIndexes(): array
    {
        return $this->indexes;
    }

    public function setIndexes(array $indexes): void
    {
        $this->indexes = $indexes;
    }

    public function findIndexDto(string $className): Index
    {
        foreach ($this->indexes as $index) {
            /** @var Index $index */
            if ($index->getClassName() === $className) {
                return $index;
            }
        }

        throw new ClassNameForIndexNotExists();
    }
}
