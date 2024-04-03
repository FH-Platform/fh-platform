<?php

namespace FHPlatform\Component\SearchEngine\SearchEngine;

use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Document;
use FHPlatform\Component\Config\DTO\Index;

interface SearchEngineInterface
{
    /** @param Document[] $documents */
    public function dataUpdate(Index $index, mixed $documents, bool $asyc = true): bool;

    // delete index by index DTO
    public function indexDelete(Index $index): void;

    // create index by index DTO
    public function indexCreate(Index $index): void;

    // delete all indexes in connection (by prefix)
    public function indexesDeleteAllInConnection(Connection $connection): void;

    // get all index names in connection (by prefix)
    public function indexesGetAllInConnection(Connection $connection, bool $byPrefix = true): array;

    // search implementation of search engine, return raw response (GET /[INDEX_NAME]/_search for ES implementation)
    public function search(Index $index, mixed $query = null): array;

    // convert search results to array of results (_source from [hits][hits] in ES implementation)
    public function convertSearchResults($results): array;
}
