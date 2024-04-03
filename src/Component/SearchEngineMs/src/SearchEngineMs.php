<?php

namespace FHPlatform\Component\SearchEngineMs;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Document;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\Persistence\Event\ChangedEntityEvent;
use FHPlatform\Component\SearchEngineMs\Connection\ConnectionFetcher;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class SearchEngineMs implements \FHPlatform\Component\SearchEngine\Adapter\SearchEngineInterface
{

    public function dataUpdate(Index $index, mixed $documents, bool $asyc = true): bool
    {
        $client = $this->fetchClientByIndex($index);

        $documentsUpsert = [];
        $documentsDelete = [];

        // TODO all in one batch request
        foreach ($documents as $document) {
            /** @var Document $document */
            if (ChangedEntityEvent::TYPE_DELETE === $document->getType()) {
                $documentsDelete[] = $document->getIdentifier();
            } else {
                // TODO move somewhere else
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

        return true;
    }

    public function indexDelete(Index $index): void
    {
        $client = $this->fetchClientByIndex($index);

        try {
            $client->request('DELETE', '/indexes/'.$index->getNameWithPrefix());
        } catch (ClientException $e) {
            // TODO
        }

        usleep(200000);
    }

    public function indexCreate(Index $index): void
    {
        $client = $this->fetchClientByIndex($index);

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
        $client = $this->fetchClientByConnection($connection);

        $indexNames = $this->indexesGetAllInConnection($connection);

        foreach ($indexNames as $indexName) {
            $client->request('DELETE', '/indexes/'.$indexName, []);
        }

        usleep(200000);
    }

    public function indexesGetAllInConnection(Connection $connection, bool $byPrefix = true): array
    {
        $client = $this->fetchClientByConnection($connection);

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

    public function search(Index $index, mixed $query = null): array
    {
        $client = $this->fetchClientByIndex($index);

        $results = $client->request('POST', '/indexes/'.$index->getNameWithPrefix().'/documents/fetch', [
            'json' => [
                'limit' => 10,
                'offset' => 0,
            ],
        ]);

        $data = json_decode($results->getBody()->getContents(), true);

        return $data;
    }

    public function convertSearchResults($results): array
    {
        $resultsResponse = [];

        foreach ($results['results'] as $result) {
            $resultsResponse[] = $result;
        }

        return $resultsResponse;
    }

    public function fetchClientByConnection(Connection $connection): Client
    {
        $client = new Client([
            'base_uri' => 'http://meilisearch:7700/',
            'headers' => [
                'Content-type' => 'application/json',
                'Authorization' => 'Bearer root',
            ],
        ]);

        return $client;
    }

    public function fetchClientByIndex(Index $index): Client
    {
        $connection = $index->getConnection();

        return $this->fetchClientByConnection($connection);
    }
}
