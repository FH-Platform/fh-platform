<?php

namespace FHPlatform\Component\SearchEngineMeilisearch\Connection;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;
use GuzzleHttp\Client;

class ConnectionFetcher
{
    public function fetchByConnection(Connection $connection): Client
    {
        return new Client(['base_uri' => 'http://meilisearch:7700/']);
    }

    public function fetchByIndex(Index $index): Client
    {
        $connection = $index->getConnection();

        return $this->fetchByConnection($connection);
    }
}
