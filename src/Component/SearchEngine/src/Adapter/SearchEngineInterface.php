<?php

namespace FHPlatform\Component\SearchEngine\Adapter;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Document;
use FHPlatform\Component\Config\DTO\Index;

interface SearchEngineInterface
{
    /** @param Document[] $documents */
    public function dataUpdate(Index $index, mixed $documents, bool $asyc = true): void;

    public function indexDelete(Index $index): void;

    public function indexCreate(Index $index): void;

    public function indexesDeleteAllInConnection(Connection $connection): void;

    public function indexesGetAllInConnection(Connection $connection, bool $byPrefix = true): array;

    public function queryResults(Index $index, mixed $query = null): array;

    public function convertResultsSource($results): array;
}
