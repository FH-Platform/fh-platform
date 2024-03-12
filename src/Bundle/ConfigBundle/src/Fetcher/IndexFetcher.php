<?php

namespace FHPlatform\ConfigBundle\Fetcher;

use FHPlatform\ConfigBundle\Fetcher\DTO\Index;
use FHPlatform\ConfigBundle\Fetcher\Global\IndexesFetcher;

class IndexFetcher
{
    public function __construct(
        private readonly IndexesFetcher $indexesFetcher,
    ) {
    }

    public function fetch(string $className): Index
    {
        foreach ($this->indexesFetcher->fetch() as $index) {
            /** @var Index $index */
            if ($index->getClassName() === $className) {
                return $index;
            }
        }

        // TODO
        throw new \Exception('Error');
    }
}
