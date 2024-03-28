<?php

namespace FHPlatform\Component\SearchEngineMs;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Document;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\Persistence\DTO\ChangedEntity;
use FHPlatform\Component\SearchEngineMs\Connection\ConnectionFetcher;
use GuzzleHttp\Exception\ClientException;

class SearchEngineMs implements \FHPlatform\Component\SearchEngine\Adapter\SearchEngineInterface
{
    private ConnectionFetcher $connectionFetcher;

    public function __construct()
    {
        $this->connectionFetcher = new ConnectionFetcher();
    }

    public function dataUpdate(Index $index, mixed $documents, bool $asyc = true): void
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        $documentsUpsert = [];
        $documentsDelete = [];

        foreach ($documents as $document) {
            /** @var Document $document */
            if (ChangedEntity::TYPE_DELETE === $document->getType()) {
                $documentsDelete[] = $document->getIdentifier();
            } else {
                $documentsUpsert[] = array_merge(['id' => $document->getIdentifier()], $document->getData());
            }
        }

        if (count($documentsUpsert)) {
            $client->request('POST', '/indexes/'.$index->getNameWithPrefix().'/documents', [
                'json' => $documentsUpsert,
            ]);
        }

        if (count($documentsDelete) > 0) {
            $client->request('POST', '/indexes/'.$index->getNameWithPrefix().'/documents/delete-batch', [
                'json' => $documentsDelete,
            ]);
        }

        if ($asyc) {
            usleep(200000);
        }
    }

    public function indexDelete(Index $index): void
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        try {
            $client->request('DELETE', '/indexes/'.$index->getNameWithPrefix());
        } catch (ClientException $e) {
            // TODO
        }

        usleep(200000);
    }

    public function indexCreate(Index $index): void
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        // TODO mapping
        $client->request('POST', '/indexes', [
            'json' => [
                'uid' => $index->getNameWithPrefix(),
                'primaryKey' => 'id',
            ],
        ]);

        usleep(200000);
    }

    public function indexesDeleteAllInConnection(Connection $connection): void
    {
        $client = $this->connectionFetcher->fetchByConnection($connection);

        $indexNames = $this->indexesGetAllInConnection($connection);

        foreach ($indexNames as $indexName) {
            $client->request('DELETE', '/indexes/'.$indexName, []);
        }

        usleep(200000);
    }

    public function indexesGetAllInConnection(Connection $connection, bool $byPrefix = true): array
    {
        $client = $this->connectionFetcher->fetchByConnection($connection);

        $results = $client->request('GET', '/indexes', []);

        $indexes = json_decode($results->getBody()->getContents(), true)['results'];

        $indexNames = [];
        foreach ($indexes as $index) {
            $uid = $index['uid'];
            if ($byPrefix) {
                if (str_starts_with($uid, $connection->getPrefix())) {
                    $indexNames[] = $uid;
                }
            } else {
                $indexNames[] = $uid;
            }
        }

        return $indexNames;
    }

    public function queryResults(Index $index, mixed $query = null): array
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        $results = $client->request('POST', '/indexes/'.$index->getNameWithPrefix().'/documents/fetch', [
            'json' => [
                'limit' => 10,
                'offset' => 0,
            ],
        ]);

        $data = json_decode($results->getBody()->getContents(), true);

        return $data;
    }

    public function convertResultsSource($results): array
    {
        $resultsResponse = [];

        foreach ($results['results'] as $result) {
            $resultsResponse[] = $result;
        }

        return $resultsResponse;
    }
}
