<?php

namespace FHPlatform\Component\SearchEngineEs;

use Elastica\Request;
use Elastica\Search;
use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Document;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\Persistence\DTO\ChangedEntityDTO;
use FHPlatform\Component\SearchEngineEs\Connection\ConnectionFetcher;
use GuzzleHttp\Client;

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

        $data = ['doc' => array_merge(['id' => $document->getIdentifier()], $document->getData()), 'doc_as_upsert' => true];

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

    public function dataUpdate(Index $index, mixed $documents, bool $asyc = false): void
    {
        $client = new Client([
            'base_uri' => 'http://elasticsearch:9200',  // TODO
            'timeout' => 2.0,
        ]);

        $documentJson = '';
        foreach ($documents as $document) {
            $data = $this->documentPrepare($document);

            $documentJson .= json_encode($data[0]) . "\n";

            if (isset($data[1])) {
                $documentJson .= json_encode($data[1]) . "\n";
            }
        }

        if ('' === $documentJson) {
            return;
        }

        $documentJson .= "\n";

        $url = '/_bulk';
        if($asyc){
            $url .= '?refresh=wait_for';
        }

        $response = $client->request('POST', $url,
            [
                'headers' => ['Content-type' => 'application/json'],
                'body' => $documentJson . "\n",
            ]
        );
    }

    public function indexDelete(Index $index): void
    {
        $index = $this->getIndex($index);

        if ($index->exists()) {
            $index->delete();
        }
    }

    public function indexCreate(Index $index): void
    {
        $index = $this->getIndex($index);

        if (!$index->exists()) {
            $index->create();

            // TODO
            /*$mappingObject = new Mapping();
            $mappingObject->setProperties($mapping);
            $mappingObject->send($index);*/
        }
    }

    public function indexesDeleteAllInConnection(Connection $connection): void
    {
        $client = $this->connectionFetcher->fetchByConnection($connection);

        $client->request(sprintf('%s*', $connection->getPrefix()), Request::DELETE)->getStatus();
    }

    public function indexesGetAllInConnection(Connection $connection, bool $byPrefix = true): array
    {
        $client = $this->connectionFetcher->fetchByConnection($connection);

        $indices = $client->getCluster()->getIndexNames();
        $indicesFiltered = [];

        foreach ($indices as $index) {
            if (str_starts_with($index, $connection->getPrefix())) {
                $indicesFiltered[] = $index;
            }
        }

        sort($indicesFiltered);

        return $indicesFiltered;
    }

    public function queryResults(Index $index, mixed $query = null, $limit = 100, $offset = 0): array
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        $index = $this->getIndex($index);

        $search = new Search($client);
        $search->addIndex($index);

        if ($query) {
            $search->setQuery($query);
        }

        return $search->search()->getResponse()->getData();
    }

    private function getIndex(Index $index): \Elastica\Index
    {
        $client = $this->connectionFetcher->fetchByIndex($index);

        return $client->getIndex($index->getNameWithPrefix());
    }
}
