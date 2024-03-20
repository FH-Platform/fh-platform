<?php

namespace FHPlatform\ClientBundle\Client\Index;

use Elastica\Request;
use FHPlatform\ClientBundle\Provider\Elastica\Connection\ConnectionFetcher;
use FHPlatform\ClientBundle\Provider\ProviderInterface;
use FHPlatform\ConfigBundle\DTO\Connection;

class IndexClientRaw
{
    public function __construct(
        private readonly  ProviderInterface $provider,
    ) {
    }

    public function getAllIndexesInConnection(Connection $connection): array
    {
        return $this->provider->indexesGetAllInConnection($connection);
    }

    public function deleteAllIndexesInConnection(Connection $connection): void
    {
        $this->provider->indexesDeleteAllInConnection($connection);
    }
}
