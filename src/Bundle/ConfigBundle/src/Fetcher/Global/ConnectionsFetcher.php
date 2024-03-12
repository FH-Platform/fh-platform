<?php

namespace FHPlatform\ConfigBundle\Fetcher\Global;

use FHPlatform\ConfigBundle\Fetcher\DTO\Connection;
use FHPlatform\ConfigBundle\Fetcher\DTO\Index;
use FHPlatform\ConfigBundle\Tag\Connection\ProviderConnection;
use FHPlatform\ConfigBundle\Tag\Data\Provider\Interface\ProviderBaseInterface;
use FHPlatform\ConfigBundle\Tag\Data\Provider\Interface\ProviderIndexInterface;
use FHPlatform\ConfigBundle\Tagged\TaggedProvider;

class ConnectionsFetcher
{
    public function __construct(
        private readonly TaggedProvider $taggedProvider,
    ) {
    }

    public function fetch(): array
    {
        $connections = [];
        foreach ($this->taggedProvider->getProvidersConnection() as $providerConnection) {
            /** @var ProviderConnection $providerConnection */
            $connection = $this->convertProviderConnectionToDto($providerConnection);

            $indexes = [];
            foreach ($this->taggedProvider->getProvidersIndex() as $providerIndex) {
                /** @var ProviderIndexInterface $providerIndex */
                if ($providerIndex->getConnection() === $providerConnection->getName()) {
                    $indexes[] = $this->convertProviderIndexToDto($providerIndex, $connection);
                }
            }

            $connection->setIndexes($indexes);

            $connections[] = $connection;
        }

        return $connections;
    }

    private function convertProviderConnectionToDto(ProviderConnection $providerConnection): Connection
    {
        return new Connection($providerConnection->getName(), $providerConnection->getIndexPrefix(), $providerConnection->getElasticaConfig());
    }

    private function convertProviderIndexToDto(ProviderIndexInterface $providerIndex, Connection $connection): Index
    {
        $className = $providerIndex->getClassName();

        // prepare decorators
        $decorators = $this->taggedProvider->getDecoratorsIndex();

        // decorate
        $mapping = $settings = $additionalConfig = [];
        $name = '';

        foreach ($decorators as $decorator) {
            if ($decorator instanceof ProviderBaseInterface and $decorator->getClassName() !== $className) {
                continue;
            }

            $mapping = $decorator->getIndexMapping($className, $mapping);
            $settings = $decorator->getIndexSettings($className, $settings);

            // TODO throw
            if ($decorator instanceof ProviderIndexInterface) {
                $name = $decorator->getIndexName($className);
                $additionalConfig = $decorator->getAdditionalConfig();
            }
        }

        return new Index($className, $connection, $name, $mapping, $settings, $additionalConfig);
    }
}
