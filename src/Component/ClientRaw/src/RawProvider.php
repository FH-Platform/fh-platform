<?php

namespace FHPlatform\Component\ClientRaw;

use FHPlatform\Component\Client\Provider\ProviderInterface;
use FHPlatform\Component\ClientRaw\Connection\ConnectionFetcher;
use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;

class RawProvider implements ProviderInterface
{
    private ConnectionFetcher $connectionFetcher;

    public function __construct()
    {
        $this->connectionFetcher = new ConnectionFetcher();
    }

    public function documentPrepare(Index $index, mixed $identifier, array $data, bool $upsert): mixed
    {
        if (!$upsert) {
            return [
                'delete' => [
                    '_index' => $index->getNameWithPrefix(),
                    '_id' => $identifier,
                ],
            ];
        }

        return [
            'update' => [
                '_index' => $index->getNameWithPrefix(),
                '_id' => $identifier,
            ],
            'doc' => $data,
        ];
    }

    public function documentsUpsert(Index $index, mixed $documents): mixed
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        // TODO mapping
        return $client->request('POST', '/_bulk', [
            'json' => $documents,
        ]);
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

        $client->request('DELETE', '/'.$index->getNameWithPrefix());
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

        return $client->request('GET', '/'.$index->getNameWithPrefix().'/_search', [
            'json' => [
                'from' => $offset,
                'size' => $limit,
            ],
        ]);
    }
}
