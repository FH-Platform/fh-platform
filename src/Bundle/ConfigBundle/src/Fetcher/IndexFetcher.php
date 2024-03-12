<?php

namespace FHPlatform\ConfigBundle\Fetcher;

use FHPlatform\ConfigBundle\Fetcher\DTO\Index;
use FHPlatform\ConfigBundle\Tag\Data\Provider\Interface\ProviderBaseInterface;
use FHPlatform\ConfigBundle\Tag\Data\Provider\Interface\ProviderIndexInterface;
use FHPlatform\ConfigBundle\Tagged\TaggedProvider;

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
            if ($decorator instanceof ProviderBaseInterface and $decorator->getClassName() !== $className) {
                continue;
            }

            $mapping = $decorator->getIndexMapping($className, $mapping);
            $settings = $decorator->getIndexSettings($className, $settings);

            // TODO throw
            if ($decorator instanceof ProviderIndexInterface) {
                $name = $decorator->getIndexName($className);
                $connection = $this->connectionFetcher->fetch($decorator->getConnection());
            }
        }

        // return
        return new Index($className, $connection, $name, $mapping, $settings);
    }
}
