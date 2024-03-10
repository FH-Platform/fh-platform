<?php

namespace FHPlatform\ClientBundle\Client\Index;

use Elastica\Index;
use FHPlatform\ClientBundle\Provider\ClientBundleProvider;

class IndexClient
{
    public function __construct(
        private readonly ClientBundleProvider $clientBundleProvider,
        private readonly IndexNameClient $indexNameClient,
    ) {
    }

    public function getIndex(string $className): Index
    {
        $indexName = $this->getIndexName($className);

        return $this->indexNameClient->getIndexByName($indexName);
    }

    public function createIndex(string $className): Index
    {
        $indexName = $this->getIndexName($className);

        $index = $this->indexNameClient->getIndexByName($indexName);

        if (!$index->exists()) {
            $indexDto = $this->clientBundleProvider->findIndexDto($className);
            $this->indexNameClient->createIndexByName($indexName, $indexDto->getMapping(), $indexDto->getSettings());
        }

        return $index;
    }

    public function deleteIndex(string $className): void
    {
        $indexName = $this->getIndexName($className);

        $this->indexNameClient->deleteIndexByName($indexName);
    }

    public function recreateIndex(string $className): Index
    {
        $this->deleteIndex($className);

        return $this->createIndex($className);
    }

    public function getIndexName(string $className): string
    {
        $indexClass = $this->clientBundleProvider->findIndexDto($className);

        $indexName = $indexClass->getName();

        return $indexClass->getConnection()->getPrefix().$indexName;
    }
}
