<?php

namespace FHPlatform\Component\ClientRaw\Connection;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;
use GuzzleHttp\Client;

class ConnectionFetcher
{
    public function fetchByConnection(Connection $connection): Client
    {
        return new Client([
            'base_uri' => 'http://elasticsearch:9200',  // TODO
            // 'port' => 9200,
            'timeout' => 2.0,
        ]);
    }

    public function fetchByIndex(Index $index): Client
    {
        $connection = $index->getConnection();

        return $this->fetchByConnection($connection);
    }
}
