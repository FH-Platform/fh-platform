<?php

namespace FHPlatform\Component\ClientRaw;

use FHPlatform\Component\Client\Provider\ProviderInterface;
use FHPlatform\Component\ClientRaw\Connection\ConnectionFetcher;
use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Document;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\Persistence\DTO\ChangedEntityDTO;

class RawProvider implements ProviderInterface
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
                'delete' => [
                    '_index' => $index->getNameWithPrefix(),
                    '_id' => $document->getIdentifier(),
                ],
            ];
        }

        return [
            [
                'create' => [
                    '_index' => $index->getNameWithPrefix(),
                    '_id' => $document->getIdentifier(),
                ],
            ],
            [
                $document->getData(),
            ],
        ];
    }

    public function documentsUpsert(Index $index, mixed $documents): mixed
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        $documentJson = '';
        foreach ($documents as $document) {
            $documentJson .= json_encode($document[0])."\n";
            $documentJson .= json_encode($document[1])."\n";

            // $documentProcessed = array_merge($documentProcessed, [$document[0], $document[1]]);
        }
        $documentJson .= "\n";

        // TODO mapping
        $response = $client->request('POST', '/_bulk',
            [
                'headers' => ['content-type' => 'application/json'],
                'body' => $documentJson."\n",
            ]
        );

        return $response;
    }

    public function documentsDelete(Index $index, mixed $documents): mixed
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        // TODO mapping
        return $client->request('POST', '/_bulk', [
            'json' => $documents,
        ]);
    }

    public function indexRefresh(Index $index): mixed
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        return $client->request('POST', '/'.$index->getNameWithPrefix().'/_refresh');
    }

    public function indexDelete(Index $index): void
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        try {
            $client->request('DELETE', '/'.$index->getNameWithPrefix());
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // TODO
        }
    }

    public function indexCreate(Index $index): mixed
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        // TODO mapping
        return $client->request('PUT', '/'.$index->getNameWithPrefix());
    }

    public function indexesDeleteAllInConnection(Connection $connection): void
    {
        $client = $this->connectionFetcher->fetchByConnection($connection);

        $client->request('DELETE', '/'.$connection->getPrefix().'*');
    }

    public function indexesGetAllInConnection(Connection $connection): array
    {
        $client = $this->connectionFetcher->fetchByConnection($connection);

        $response = $client->request('GET', '/_aliases');

        dd($response->getBody()->getContents());
    }

    public function searchPrepare(Index $index, mixed $query = null): mixed
    {
        // TODO: Implement searchPrepare() method.
    }

    public function searchResults(Index $index, mixed $query = null, $limit = 100, $offset = 0): mixed
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        $results = $client->request('GET', '/'.$index->getNameWithPrefix().'/_search', [
            'json' => [
                'from' => $offset,
                'size' => $limit,
            ],
        ]);

        $data = json_decode($results->getBody()->getContents(), true);

        return $data;
    }
}
