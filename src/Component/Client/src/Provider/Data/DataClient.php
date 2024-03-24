<?php

namespace FHPlatform\Component\Client\Provider\Data;

use FHPlatform\Component\Client\Provider\ProviderInterface;
use FHPlatform\Component\Config\DTO\Document;

class DataClient
{
    public function __construct(
        private readonly ProviderInterface $provider,
    ) {
    }

    /** @param Document[] $documents */
    public function syncDocuments(array $documents): array
    {
        if (0 === count($documents)) {
            return [];
        }

        // group indexes and documents by connection and index
        $documentsGrouped = $this->groupDocuments($documents);

        $responses = [];
        foreach ($documentsGrouped as $connectionName => $indexes) {
            foreach ($indexes as $indexName => $data) {
                $index = $data['index'];

                // do the upsert/delete for each index on connection

                if (count($data['documents']) > 0) {
                    $responses[] = $this->provider->documentsUpdate($index, $data['documents']);
                }

                // refresh index
                $this->provider->indexRefresh($index);
            }
        }

        // return array of responses
        return $responses;
    }

    /** @param Document[] $documents */
    private function groupDocuments(array $documents): array
    {
        $documentsGrouped = [];

        foreach ($documents as $document) {
            $index = $document->getIndex();

            $connectionName = $index->getConnection()->getName();
            $indexNameWithPrefix = $index->getNameWithPrefix();

            $documentsGrouped[$connectionName][$indexNameWithPrefix]['index'] = $index;
            $documentsGrouped[$connectionName][$indexNameWithPrefix]['documents'][] = $this->provider->documentPrepare($document);
        }

        return $documentsGrouped;
    }
}
