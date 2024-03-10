<?php

namespace FHPlatform\ClientBundle\Client\Index;

use FHPlatform\ConfigBundle\Fetcher\DTO\Index;
use FHPlatform\ClientBundle\Provider\ClientBundleProvider;

class IndexAllClient
{
    public function __construct(
        private readonly ClientBundleProvider $clientBundleProvider,
        private readonly IndexClient $indexClient,
    ) {
    }

    public function getAllIndexes(): array
    {
        $indexDtos = $this->clientBundleProvider->getIndexes();
        $indexes = [];
        foreach ($indexDtos as $indexDto) {
            /* @var  Index $indexDto */
            $indexes[] = $this->indexClient->getIndex($indexDto->getClassName());
        }

        return $indexes;
    }

    public function createAllIndexes(): array
    {
        $indexDtos = $this->clientBundleProvider->getIndexes();
        $indexes = [];
        foreach ($indexDtos as $indexDto) {
            /* @var  Index $indexDto */
            $indexes[] = $this->indexClient->createIndex($indexDto->getClassName());
        }

        return $indexes;
    }

    public function deleteAllIndexes(): void
    {
        $indexDtos = $this->clientBundleProvider->getIndexes();
        foreach ($indexDtos as $indexDto) {
            /* @var  Index $indexDto */
            $this->indexClient->deleteIndex($indexDto->getClassName());
        }
    }

    public function recreateAllIndexes(): void
    {
        $indexDtos = $this->clientBundleProvider->getIndexes();
        foreach ($indexDtos as $indexDto) {
            /* @var  Index $indexDto */
            $this->indexClient->recreateIndex($indexDto->getClassName());
        }
    }
}
