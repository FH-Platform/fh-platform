<?php

namespace FHPlatform\Component\SearchEngineEs;

use Elastica\Search;
use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Document;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\SearchEngine\SearchEngine\SearchEngineInterface;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;

class SearchEngineEs implements SearchEngineInterface
{
    public function __construct(
        private readonly LoggerInterface $elasticaLogger
    ) {
    }

    private function documentPrepare(Document $document): mixed
    {
        $index = $document->getIndex();

        if (Document::TYPE_DELETE === $document->getType()) {
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

    public function dataUpdate(Index $index, mixed $documents, bool $asyc = true): bool
    {
        $client = $this->fetchClientByIndex($index);
        $configClient = $index->getConnection()->getConfigClient();
        $servers = $configClient['servers'];

        $server = $servers[array_rand($servers)];

        $baseUri = 'http://'.$server['host'].':'.$server['port'];
        $headers = $server['headers'];

        $clientGuzzle = new Client([
            'base_uri' => $baseUri,
            'headers' => $headers,
        ]);

        $datas = [];
        foreach ($documents as $document) {
            $data = $this->documentPrepare($document);

            $datas[] = $data[0];

            if (isset($data[1])) {
                $datas[] = $data[1];
            }
        }

        $documentJsons = '';
        foreach ($datas as $data) {
            $documentJsons .= json_encode($data)."\n";
        }

        if ('' === $documentJsons) {
            return true;
        }

        $documentJsons .= "\n";

        $url = '_bulk';
        $response = $clientGuzzle->request('POST', '/'.$url,
            [
                'headers' => ['Content-type' => 'application/json'],
                'body' => $documentJsons."\n",
            ]
        );

        $this->elasticaLogger->debug('Elastica Request', [
            'request' => [
                'path' => $url,
                'method' => 'POST',
                'data' => $datas,
                'query' => [],
                'contentType' => 'application/json',
                'connection' => $server,
            ],
            'response' => json_decode($response->getBody()->getContents(), true),
            'responseStatus' => $response->getStatusCode(),
        ]);

        if ($asyc) {
            $this->getIndex($index)->refresh();
        }

        return true;
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
        $indexElastica = $this->getIndex($index);

        if (!$indexElastica->exists()) {
            $indexElastica->create($index->getSettings());

            if (count($index->getMapping()) > 0) {
                // TODO
                /*$mappingObject = new Mapping();
                $mappingObject->setProperties($index->getMapping());
                $mappingObject->send($indexElastica);*/
            }
        }
    }

    public function indexesDeleteAllInConnection(Connection $connection): void
    {
        $indexes = $this->indexesGetAllInConnection($connection);

        foreach ($indexes as $index) {
            $this->indexDelete(new Index($connection, '', false, '', $index, []));
        }
    }

    public function indexesGetAllInConnection(Connection $connection, bool $byPrefix = true): array
    {
        $client = $this->fetchClientByConnection($connection);

        $indexes = $client->getCluster()->getIndexNames();
        $indexesFiltered = [];

        foreach ($indexes as $index) {
            if ($byPrefix) {
                if (str_starts_with($index, $connection->getPrefix())) {
                    $indexesFiltered[] = $index;
                }
            } else {
                $indexesFiltered[] = $index;
            }
        }

        sort($indexesFiltered);

        return $indexesFiltered;
    }

    public function search(Index $index, array $query = []): array
    {
        $client = $this->fetchClientByIndex($index);

        $index = $this->getIndex($index);

        $search = new Search($client);
        $search->addIndex($index);

        if ($query) {
            $search->setQuery($query);
        }

        $data = $search->search()->getResponse()->getData();

        return $data;
    }

    private function getIndex(Index $index): \Elastica\Index
    {
        $client = $this->fetchClientByIndex($index);

        return $client->getIndex($index->getNameWithPrefix());
    }

    public function convertResultsToSources($results): array
    {
        $resultsResponse = [];

        foreach ($results['hits']['hits'] as $result) {
            $resultsResponse[] = $result['_source'];
        }

        return $resultsResponse;
    }

    public function convertResultsToIdentifiers($results): array
    {
        $ids = [];

        foreach ($results['hits']['hits'] as $result) {
            $ids[] = $result['_id'];
        }

        $ids = array_unique($ids);

        return $ids;
    }

    public function fetchClientByConnection(Connection $connection): \Elastica\Client
    {
        $client = new \Elastica\Client($connection->getConfigClient());

        $client->setLogger($this->elasticaLogger);

        return $client;
    }

    public function fetchClientByIndex(Index $index): \Elastica\Client
    {
        $connection = $index->getConnection();

        return $this->fetchClientByConnection($connection);
    }
}
