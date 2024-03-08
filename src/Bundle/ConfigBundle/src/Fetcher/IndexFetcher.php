<?php

namespace FHPlatform\ConfigBundle\Fetcher;

use FHPlatform\ConfigBundle\Fetcher\DTO\Index;
use FHPlatform\ConfigBundle\TaggedProvider\TaggedProvider;
use FHPlatform\ConfigBundle\TagProvider\Index\ProviderBase;
use FHPlatform\ConfigBundle\TagProvider\Index\ProviderIndex;

class IndexFetcher
{
    public function __construct(
        private readonly TaggedProvider $taggedProvider,
        private readonly ConnectionFetcher $connectionFetcher,
    ) {
    }

    public function fetch(string $className): Index
    {
        // prepare decorators
        $decorators = $this->taggedProvider->getDecoratorsIndex();

        // decorate
        $mapping = $settings = [];
        $name = '';
        $connection = null;

        foreach ($decorators as $decorator) {
            if ($decorator instanceof ProviderIndex and $decorator->getClassName() !== $className) {
                continue;
            }

            $name = $decorator->getIndexName($className, $name);
            $mapping = $decorator->getIndexMapping($className, $mapping);
            $settings = $decorator->getIndexSettings($className, $settings);

            if ($decorator instanceof ProviderBase) {
                $connection = $this->connectionFetcher->fetch($decorator->getConnection());
            }
        }

        // return
        return new Index($className, $connection, $name, $mapping, $settings);
    }
}
