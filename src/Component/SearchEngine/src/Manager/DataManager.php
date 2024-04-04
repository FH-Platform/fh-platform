<?php

namespace FHPlatform\Component\SearchEngine\Manager;

use FHPlatform\Component\Config\Builder\DocumentBuilder;
use FHPlatform\Component\Config\DTO\Document;
use FHPlatform\Component\SearchEngine\SearchEngine\SearchEngineInterface;

class DataManager
{
    public function __construct(
        private readonly SearchEngineInterface $searchEngine,
        private readonly DocumentBuilder $documentBuilder,
    ) {
    }

    // TODO remove
    public function insertRaw(string $className, array $data, mixed $identifierValue): void
    {
        $documents[] = $this->documentBuilder->buildRaw($className, $data, $identifierValue, Document::TYPE_CREATE);
        $this->syncDocuments($documents);
    }

    public function syncDocuments(array $documentsGrouped): void
    {
        if (0 === count($documentsGrouped)) {
            return;
        }

        foreach ($documentsGrouped as $connectionName => $indexes) {
            foreach ($indexes as $indexName => $data) {
                $index = $data['index'];

                // do the create/update/delete for each index on connection
                $this->searchEngine->dataUpdate($index, $data['documents']);
            }
        }
    }
}
