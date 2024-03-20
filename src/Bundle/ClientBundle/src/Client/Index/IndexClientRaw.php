<?php

namespace FHPlatform\ClientBundle\Client\Index;

use Elastica\Index;
use Elastica\Request;
use FHPlatform\ClientBundle\Provider\Elastica\Connection\ConnectionFetcher;
use FHPlatform\ConfigBundle\DTO\Connection;

class IndexClientRaw
{
    public function __construct(
        private readonly ConnectionFetcher $connectionFetcher,
    ) {
    }

    public function getIndexesNameByPrefix(Connection $connection): array
    {
        $client = $this->connectionFetcher->fetchByConnection($connection);

        $indices = $client->getCluster()->getIndexNames();
        $indicesFiltered = [];

        foreach ($indices as $index) {
            if (str_starts_with($index, $connection->getPrefix())) {
                $indicesFiltered[] = $index;
            }
        }

        sort($indicesFiltered);

        return $indicesFiltered;
    }

    public function deleteAllIndexesByPrefix(Connection $connection): void
    {
        $client = $this->connectionFetcher->fetchByConnection($connection);

        $client->request(sprintf('%s*', $connection->getPrefix()), Request::DELETE)->getStatus();
    }

    public function createIndexByName(Connection $connection, string $indexName, array $mappings = [], $settings = []): Index
    {
        $client = $this->connectionFetcher->fetchByConnection($connection);

        $indexNameWithPrefix = $connection->getPrefix().$indexName;
        $index = $client->getIndex($indexNameWithPrefix);

        if (!$index->exists()) {
            $index->create();

            // TODO
            /*$mappingObject = new Mapping();
            $mappingObject->setProperties($mapping);
            $mappingObject->send($index);*/
        }

        return $index;
    }
}
