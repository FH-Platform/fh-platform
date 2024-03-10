<?php

namespace FHPlatform\ClientBundle\Client\Index;

use Elastica\Index;
use Elastica\Request;
use FHPlatform\ClientBundle\Client\ElasticaClient;
use FHPlatform\ClientBundle\Provider\ClientBundleProvider;

class IndexNameClient
{
    public function __construct(
        private readonly ElasticaClient $client,
        private readonly ClientBundleProvider $clientBundleProvider,
    ) {
    }

    public function getIndexByName(string $indexName): Index
    {
        return $this->client->getIndex($indexName);
    }

    public function createIndexByName(string $indexName, array $mappings = [], $settings = []): Index
    {
        $index = $this->getIndexByName($indexName);

        if (!$index->exists()) {
            $index->create($settings);

            // TODO
            /*$mappingObject = new Mapping();
            $mappingObject->setProperties($mapping);
            $mappingObject->send($index);*/
        }

        return $index;
    }

    public function deleteIndexByName(string $indexName): void
    {
        $index = $this->client->getIndex($indexName);

        if ($index->exists()) {
            $index->delete();
        }
    }

    public function getIndexesNameByPrefix(): array
    {
        $indexPrefix = $this->clientBundleProvider->getConnections()[0]->getPrefix();

        $indices = $this->client->getCluster()->getIndexNames();
        $indicesFiltered = [];

        foreach ($indices as $index) {
            if (str_starts_with($index, $indexPrefix)) {
                $indicesFiltered[] = $index;
            }
        }

        sort($indicesFiltered);

        return $indicesFiltered;
    }

    public function deleteAllIndexesByPrefix(): void
    {
        $indexPrefix = $this->clientBundleProvider->getConnections()[0]->getPrefix();

        $this->client->request(sprintf('%s*', $indexPrefix), Request::DELETE)->getStatus();
    }
}
