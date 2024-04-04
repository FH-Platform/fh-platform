<?php

namespace FHPlatform\Component\SearchEngine\Manager;

use FHPlatform\Component\SearchEngine\SearchEngine\SearchEngineInterface;

class DataManager
{
    public function __construct(
        private readonly SearchEngineInterface $searchEngine,
    ) {
    }

    public function syncDocuments(array $documentsGrouped): void
    {
        if (0 === count($documentsGrouped)) {
            return;
        }

        foreach ($documentsGrouped as $connectionName => $indexes) {
            foreach ($indexes as $indexName => $data) {
                $index = $data['index'];

                // create/update/delete for each index on connection
                $this->searchEngine->dataUpdate($index, $data['documents']);
            }
        }
    }
}
