<?php

namespace FHPlatform\ClientBundle\Client\Index;

use Elastica\Index\Settings;
use FHPlatform\ClientBundle\Connection\ConnectionFetcher;
use FHPlatform\ConfigBundle\Fetcher\DTO\Index;

class IndexClient
{
    public function __construct(
        private readonly ConnectionFetcher $connectionFetcher,
    ) {
    }

    public  function deleteIndex(Index $index):void
    {
        $client = $this->connectionFetcher->fetch($index->getConnection());

        $indexNameWithPrefix = $index->getConnection()->getPrefix().$index->getName();
        $index = $client->getIndex($indexNameWithPrefix);

        if ($index->exists()) {
            $index->delete();
        }
    }

    public  function createIndex(Index $index): \Elastica\Index
    {
        $client = $this->connectionFetcher->fetch($index->getConnection());

        $indexNameWithPrefix = $index->getConnection()->getPrefix().$index->getName();
        $index = $client->getIndex($indexNameWithPrefix);

        if (!$index->exists()) {
            if (!$index->exists()) {
                $index->create();

                // TODO
                /*$mappingObject = new Mapping();
                $mappingObject->setProperties($mapping);
                $mappingObject->send($index);*/
            }
        }

        return $index;
    }

    public  function recreateIndex(Index $index): \Elastica\Index
    {
        $this->deleteIndex($index);

        return $this->createIndex($index);
    }
}
