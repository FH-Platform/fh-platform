<?php

namespace FHPlatform\Component\SearchEngineMs\Connection;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;
use GuzzleHttp\Client;

class ConnectionFetcher
{
    public function fetchByConnection(Connection $connection): Client
    {
        $client = new Client([
            'base_uri' => 'http://meilisearch:7700/',
            'headers' => [
                'Content-type' => 'application/json',
                'Authorization' => 'Bearer root',
            ],
        ]);


        return  $client;
    }

    public function fetchByIndex(Index $index): Client
    {
        $connection = $index->getConnection();

        return $this->fetchByConnection($connection);
    }
}
