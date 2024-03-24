<?php

namespace FHPlatform\Component\SearchEngineMeilisearch;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\SearchEngineMeilisearch\Connection\ConnectionFetcher;
use GuzzleHttp\Exception\ClientException;

class SearchEngineAdapter implements \FHPlatform\Component\SearchEngine\Adapter\SearchEngineAdapter
{
    private ConnectionFetcher $connectionFetcher;

    public function __construct()
    {
        $this->connectionFetcher = new ConnectionFetcher();
    }

    public function dataUpdate(Index $index, mixed $documents): void
    {

    }

    public function indexRefresh(Index $index): void
    {
        //$client = $this->connectionFetcher->fetchByIndex($index);

        //$response = $client->request('POST', '/'.$index->getNameWithPrefix().'/_refresh');
    }

    public function indexDelete(Index $index): void
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        try {
            $client->request('DELETE', '/indexes/'.$index->getNameWithPrefix());
        } catch (ClientException $e) {
            // TODO
        }
    }

    public function indexCreate(Index $index): void
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        // TODO mapping
        $client->request('POST', '/indexes', [
            'headers' => [
                'Content-type' => 'application/json',
                'Authorization' => 'Bearer root',
            ],
            'json' => [
                "uid" => $index->getNameWithPrefix(),
                "primaryKey" => "id",
            ]
        ]);
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
