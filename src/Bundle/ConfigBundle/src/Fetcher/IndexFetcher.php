<?php

namespace FHPlatform\ConfigBundle\Fetcher;

use FHPlatform\ConfigBundle\Fetcher\DTO\Index;
use FHPlatform\ConfigBundle\Tag\Data\Provider\Interface\ProviderBaseInterface;
use FHPlatform\ConfigBundle\Tag\Data\Provider\Interface\ProviderIndexInterface;
use FHPlatform\ConfigBundle\Tag\Data\Provider\ProviderIndex;
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
            if ($decorator instanceof ProviderIndexInterface and $decorator->getClassName() !== $className) {
                continue;
            }

            $name = $decorator->getIndexName($className, $name);
            $mapping = $decorator->getIndexMapping($className, $mapping);
            $settings = $decorator->getIndexSettings($className, $settings);

            // TODO
            if ($decorator instanceof ProviderBaseInterface) {
                $connection = $this->connectionFetcher->fetch($decorator->getConnection());
            }
        }

        // return
        return new Index($className, $connection, $name, $mapping, $settings);
    }
}
