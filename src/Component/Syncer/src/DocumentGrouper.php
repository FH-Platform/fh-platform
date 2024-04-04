<?php

namespace FHPlatform\Component\Syncer;

use FHPlatform\Component\Config\DTO\Document;

class DocumentGrouper
{
    /** @param Document[] $documents */
    public function groupDocuments(array $documents): array
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
