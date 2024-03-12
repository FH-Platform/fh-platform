<?php

namespace FHPlatform\ClientBundle\Client\Index;

use FHPlatform\ConfigBundle\DTO\Connection;

class IndexClientConnection
{
    public function __construct(
        private readonly IndexClient $indexClient,
    ) {
    }

    /** @param Connection[] $connections */
    public function deleteAll(array $connections): void
    {
        foreach ($connections as $connection) {
            foreach ($connection->getIndexes() as $index) {
                $this->indexClient->deleteIndex($index);
            }
        }
    }
}
