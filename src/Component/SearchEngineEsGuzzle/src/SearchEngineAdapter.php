<?php

namespace FHPlatform\Component\SearchEngineEsGuzzle;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Document;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\Persistence\DTO\ChangedEntityDTO;
use FHPlatform\Component\SearchEngineEsGuzzle\Connection\ConnectionFetcher;
use GuzzleHttp\Exception\ClientException;

class SearchEngineAdapter implements \FHPlatform\Component\SearchEngine\Adapter\SearchEngineAdapter
{
    private ConnectionFetcher $connectionFetcher;

    public function __construct()
    {
        $this->connectionFetcher = new ConnectionFetcher();
    }

    public function documentPrepare(Document $document): mixed
    {
        $index = $document->getIndex();

        if (ChangedEntityDTO::TYPE_DELETE === $document->getType()) {
            return [
                [
                    'delete' => [
                        '_index' => $index->getNameWithPrefix(),
                        '_id' => $document->getIdentifier(),
                    ],
                ],
            ];
        }

        $data = ['doc' => $document->getData(), 'doc_as_upsert' => true];

        return [
            [
                'update' => [
                    '_index' => $index->getNameWithPrefix(),
                    '_id' => $document->getIdentifier(),
                ],
            ],
            $data,
        ];
    }

    public function dataUpdate(Index $index, mixed $documents): void
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        $documentJson = '';
        foreach ($documents as $document) {
            $data = $this->documentPrepare($document);

            $documentJson .= json_encode($data[0])."\n";

            if (isset($data[1])) {
                $documentJson .= json_encode($data[1])."\n";
            }
        }

        if ('' === $documentJson) {
            return;
        }

        $documentJson .= "\n";

        // TODO mapping
        $response = $client->request('POST', '/_bulk',
            [
                'headers' => ['Content-type' => 'application/json'],
                'body' => $documentJson."\n",
            ]
        );
    }

    public function indexDelete(Index $index): void
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        try {
            $client->request('DELETE', '/'.$index->getNameWithPrefix());
        } catch (ClientException $e) {
            // TODO
        }
    }

    public function indexCreate(Index $index): void
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        try {
            // TODO mapping
            $client->request('PUT', '/'.$index->getNameWithPrefix());
        } catch (ClientException $ce) {
            $type = json_decode($ce->getResponse()->getBody()->getContents(), true)['error']['type'];

            if ('resource_already_exists_exception' === $type) {
                return;
            }

            throw $ce;
        }
    }

    public function indexesDeleteAllInConnection(Connection $connection): void
    {
        $client = $this->connectionFetcher->fetchByConnection($connection);

        $client->request('DELETE', '/'.$connection->getPrefix().'*');
    }

    public function indexesGetAllInConnection(Connection $connection, bool $byPrefix = true): array
    {
        $client = $this->connectionFetcher->fetchByConnection($connection);

        $response = $client->request('GET', '/_aliases');

        $indexes = json_decode($response->getBody()->getContents(), true);

        $indexesFiltered = [];
        foreach ($indexes as $name => $conf) {
            if (str_starts_with($name, $connection->getPrefix())) {
                $indexesFiltered[] = $name;
            }
        }

        sort($indexesFiltered);

        return $indexesFiltered;
    }

    public function queryResults(Index $index, mixed $query = null, $limit = 100, $offset = 0): array
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        $results = $client->request('GET', '/'.$index->getNameWithPrefix().'/_search', [
            'json' => [
                'size' => $limit,
                'from' => $offset,
            ],
        ]);

        $data = json_decode($results->getBody()->getContents(), true);

        return $data;
    }
}
