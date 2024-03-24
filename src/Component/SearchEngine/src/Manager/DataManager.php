<?php

namespace FHPlatform\Component\SearchEngine\Manager;

use FHPlatform\Component\Config\DTO\Document;
use FHPlatform\Component\SearchEngine\Adapter\SearchEngineAdapter;

class DataManager
{
    public function __construct(
        private readonly SearchEngineAdapter $adapter,
    ) {
    }

    /** @param Document[] $documents */
    public function syncDocuments(array $documents): void
    {
        if (0 === count($documents)) {
            return;
        }

        // group indexes and documents by connection and index
        $documentsGrouped = $this->groupDocuments($documents);

        $responses = [];
        foreach ($documentsGrouped as $connectionName => $indexes) {
            foreach ($indexes as $indexName => $data) {
                $index = $data['index'];

                // do the upsert/delete for each index on connection

                if (count($data['documents']) > 0) {
                    $this->adapter->documentsUpdate($index, $data['documents']);
                }

                // refresh index
                $this->adapter->indexRefresh($index);
            }
        }
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
            $documentsGrouped[$connectionName][$indexNameWithPrefix]['documents'][] = $document;
        }

        return $documentsGrouped;
    }
}
