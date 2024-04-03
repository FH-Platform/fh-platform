<?php

namespace FHPlatform\Component\FilterToEsDsl\Query;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\SearchEngine\Manager\QueryManager;

class SearchClassName
{
    public function __construct(
        private readonly ConnectionsBuilder $connectionsBuilder,
        private readonly Search $search,
    ) {
    }

    public function search(string $className, array $filters = [], string $type = QueryManager::TYPE_IDENTIFIERS): array
    {
        $index = $this->connectionsBuilder->fetchIndexesByClassName($className)[0];

        return $this->search->search($index, $filters, $type);
    }
}
