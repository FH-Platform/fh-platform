<?php

namespace FHPlatform\Component\SearchEngineMeilisearch;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Document;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\Persistence\DTO\ChangedEntityDTO;
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
        $client = $this->connectionFetcher->fetchByIndex($index);

        $documentsUpsert = [];
        $documentsDelete = [];

        foreach ($documents as $document) {
            /** @var Document $document */
            if (ChangedEntityDTO::TYPE_DELETE === $document->getType()) {
                $documentsDelete[] = $document->getIdentifier();
            } else {
                $documentsUpsert[] = $document->getData();
            }
        }

        if (count($documentsUpsert)) {
            $r = $client->request('PUT', '/indexes/' . $index->getNameWithPrefix().'/documents', [
                'json' => $documentsUpsert,
            ]);

            sleep(5);

            $results = $client->request('POST', '/indexes/' . $index->getNameWithPrefix() . '/documents/fetch', [
                'json' => [
                    'limit' => 1000,
                    'offset' => 0,
                ],
            ]);

            dd($results->getBody()->getContents());
        }

        if (count($documentsDelete) > 0) {
            $client->request('POST', '/indexes/' . $index->getNameWithPrefix().'/documents/delete-batch', [
                'json' => $documentsUpsert,
            ]);
        }

        sleep(2);
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
            $client->request('DELETE', '/indexes/' . $index->getNameWithPrefix());
        } catch (ClientException $e) {
            // TODO
        }
    }

    public function indexCreate(Index $index): void
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        // TODO mapping
        $client->request('POST', '/indexes', [
            'json' => [
                "uid" => $index->getNameWithPrefix(),
                "primaryKey" => "id",
            ]
        ]);

        usleep(100000);
    }

    public function indexesDeleteAllInConnection(Connection $connection): void
    {

    }

    public function indexesGetAllInConnection(Connection $connection): array
    {

    }

    public function queryResults(Index $index, mixed $query = null, $limit = 100, $offset = 0): array
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        $results = $client->request('POST', '/indexes/' . $index->getNameWithPrefix() . '/documents/fetch', [
            'json' => [
                //"q" => "american ninja"
                'limit' => $limit,
                'offset' => $offset,
            ],
        ]);

        $data = json_decode($results->getBody()->getContents(), true);

        dump($data);

        return $data;
    }
}
