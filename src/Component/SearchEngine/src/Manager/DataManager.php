<?php

namespace FHPlatform\Component\SearchEngine\Manager;

use FHPlatform\Component\Config\Builder\DocumentBuilder;
use FHPlatform\Component\Config\DTO\Document;
use FHPlatform\Component\Persistence\DTO\ChangedEntity;
use FHPlatform\Component\SearchEngine\Adapter\SearchEngineInterface;

class DataManager
{
    public function __construct(
        private readonly SearchEngineInterface $adapter,
        private readonly DocumentBuilder $documentBuilder,
    ) {
    }

    // TODO remove
    public function insertRaw(string $className, array $data, mixed $identifierValue): void
    {
        $documents[] = $this->documentBuilder->buildRaw($className, $data, $identifierValue, ChangedEntity::TYPE_CREATE);
        $this->syncDocuments($documents);
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
                    $this->adapter->dataUpdate($index, $data['documents']);
                }
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
