<?php

namespace FHPlatform\Component\Filter;

use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\SearchEngine\Manager\QueryManager;

class FilterQuery
{
    public function __construct(
    private readonly   QueryManager $queryManager
    )
    {
    }

    public function search(Index $index, array $filters = [], $limit = 100, $offset = 0): array
    {
        $results = [];


        return $this->queryManager->getResults($index, null, 10, 0, QueryManager::TYPE_IDENTIFIERS);
    }
}
