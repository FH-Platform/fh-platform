<?php

namespace FHPlatform\Component\SearchEngineEs;

use Elastica\Mapping;
use Elastica\Search;
use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Document;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\SearchEngineEs\Connection\ConnectionFetcher;
use GuzzleHttp\Client;

class SearchEngineEs implements \FHPlatform\Component\SearchEngine\Adapter\SearchEngineInterface
{
    public function __construct(
        private readonly ConnectionFetcher $connectionFetcher,
    ) {
    }

    public function documentPrepare(Document $document): mixed
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
        $client = $this->connectionFetcher->fetchClientByIndex($index);

        $client = new Client([
            'base_uri' => 'http://elasticsearch:9200',  // TODO
            'headers' => [
                'Authorization' => 'Basic ZWxhc3RpYzplbGFzdGlj',
            ],
        ]);

        $documentJson = '';
        foreach ($documents as $document) {
            $data = $this->documentPrepare($document);

            $documentJson .= json_encode($data[0])."\n";

            if (isset($data[1])) {
                $documentJson .= json_encode($data[1])."\n";
            }
        }

        if ('' === $documentJson) {
            return true;
        }

        $documentJson .= "\n";

        $response = $client->request('POST', '/_bulk',
            [
                'headers' => ['Content-type' => 'application/json'],
                'body' => $documentJson."\n",
            ]
        );

        if ($asyc) {
            $client->request('POST', '/'.$index->getNameWithPrefix().'/_refresh');
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
        $client = $this->connectionFetcher->fetchClientByConnection($connection);

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

    public function search(Index $index, mixed $query = null): array
    {
        $client = $this->connectionFetcher->fetchClientByIndex($index);

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
        $client = $this->connectionFetcher->fetchClientByIndex($index);

        return $client->getIndex($index->getNameWithPrefix());
    }

    public function convertSearchResults($results): array
    {
        $resultsResponse = [];

        foreach ($results['hits']['hits'] as $result) {
            $resultsResponse[] = $result['_source'];
        }

        return $resultsResponse;
    }
}
