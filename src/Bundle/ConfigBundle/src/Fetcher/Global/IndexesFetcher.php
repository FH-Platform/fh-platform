<?php

namespace FHPlatform\ConfigBundle\Fetcher\Global;

use FHPlatform\ConfigBundle\Fetcher\ConnectionFetcher;
use FHPlatform\ConfigBundle\Fetcher\DTO\Index;
use FHPlatform\ConfigBundle\Tag\Data\Provider\Interface\ProviderBaseInterface;
use FHPlatform\ConfigBundle\Tag\Data\Provider\Interface\ProviderIndexInterface;
use FHPlatform\ConfigBundle\Tag\Data\Provider\ProviderIndex;
use FHPlatform\ConfigBundle\Tagged\TaggedProvider;

class IndexesFetcher
{
    public function __construct(
        private readonly TaggedProvider $taggedProvider,
        private readonly ConnectionFetcher $connectionFetcher,
    ) {
    }

    public function fetch(): array
    {
        $indexes = [];
        foreach ($this->taggedProvider->getProvidersIndex() as $indexProvider) {
            /** @var ProviderIndex $indexProvider */
            $className = $indexProvider->getClassName();

            // prepare decorators
            $decorators = $this->taggedProvider->getDecoratorsIndex();

            // decorate
            $mapping = $settings = $additionalConfig = [];
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
                    $additionalConfig = $decorator->getAdditionalConfig();
                }
            }

            $indexes[] = new Index($className, $connection, $name, $mapping, $settings, $additionalConfig);
        }

        return $indexes;
    }
}
