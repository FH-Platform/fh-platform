<?php

namespace FHPlatform\ConfigBundle\Provider;

use FHPlatform\ConfigBundle\DTO\Index;

class ConnectionsProvider
{
    public function __construct(
        private readonly array $connections,
    ) {
    }

    public function getConnections(): array
    {
        return $this->connections;
    }

    /** @return Index[] */
    public function fetchIndexesByClassName(string $className): array
    {
        $indexes = [];
        foreach ($this->getConnections() as $connection) {
            foreach ($connection->getIndexes() as $index) {
                if ($index->getClassName() === $className) {
                    $indexes[] = $index;
                }
            }
        }

        return $indexes;
    }
}
