<?php

namespace FHPlatform\Component\Config\Serializer;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\DTO\Document;

class DocumentSerializer
{
    public function __construct(
        private readonly ConnectionsBuilder $connectionsBuilder,
    ) {
    }

    public function serialize(Document $document): array
    {
        return [
            'index' => [
                'connectionName' => $document->getIndex()->getConnection()->getName(),
                'className' => $document->getIndex()->getClassName(),
            ],
            'identifierValue' => $document->getIdentifierValue(),
            'data' => $document->getData(),
            'type' => $document->getType(),
        ];
    }

    private function deserialize(array $documentSerialized): Document
    {
        $connectionName = $documentSerialized['index']['connectionName'];
        $className = $documentSerialized['index']['className'];

        $index = $this->connectionsBuilder->fetchIndexesByConnectionNameAndClassName($connectionName, $className);
        $identifierValue = $documentSerialized['identifierValue'];
        $data = $documentSerialized['data'];
        $type = $documentSerialized['type'];

        return new Document($index, $identifierValue, $data, $type);
    }
}
