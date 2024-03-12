<?php

namespace FHPlatform\ConfigBundle\Fetcher;

use FHPlatform\ConfigBundle\DTO\Connection;
use FHPlatform\ConfigBundle\DTO\Index;
use FHPlatform\ConfigBundle\Fetcher\Global\ConnectionsFetcher;

class IndexFetcher
{
    public function __construct(
        private readonly ConnectionsFetcher $connectionsFetcher
    ) {
    }

    public function fetch(string $className): Index
    {
        foreach ($this->connectionsFetcher->fetch() as $connection) {
            /** @var Connection $connection */
            foreach ($connection->getIndexes() as $index) {
                /* @var Connection $connection */

                if ($index->getClassName() === $className) {
                    return $index;
                }
            }
        }

        // TODO
        throw new \Exception('Error');
    }
}
