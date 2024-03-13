<?php

namespace FHPlatform\ConfigBundle\Fetcher;

use FHPlatform\ConfigBundle\DTO\Index;
use FHPlatform\ConfigBundle\Fetcher\Global\ConnectionsFetcher;

class IndexFetcher
{
    public function __construct(
        private readonly ConnectionsFetcher $connectionsFetcher
    ) {
    }

    /** @return Index[] */
    public function fetchIndexesByClassName(string $className): array
    {
        $indexes = [];
        foreach ($this->connectionsFetcher->fetch() as $connection) {
            foreach ($connection->getIndexes() as $index) {
                if ($index->getClassName() === $className) {
                    $indexes[] = $index;
                }
            }
        }

        return $indexes;
    }
}
