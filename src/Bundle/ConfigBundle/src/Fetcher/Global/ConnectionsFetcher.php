<?php

namespace FHPlatform\ConfigBundle\Fetcher\Global;

use FHPlatform\ConfigBundle\DTO\Connection;
use FHPlatform\ConfigBundle\DTO\Index;
use FHPlatform\ConfigBundle\Tag\Connection\ProviderConnection;
use FHPlatform\ConfigBundle\Tag\Decorator\Interface\DecoratorIndexInterface;
use FHPlatform\ConfigBundle\Tag\Provider\Interface\ProviderBaseInterface;
use FHPlatform\ConfigBundle\Tag\Provider\Interface\ProviderIndexInterface;
use FHPlatform\ConfigBundle\Tagged\TaggedProvider;

class ConnectionsFetcher
{
    public function __construct(
        private readonly TaggedProvider $taggedProvider,
    ) {
    }

    /** @return Connection[] */
    public function fetch(): array
    {
        $providersConnection = $this->taggedProvider->getProvidersConnection();
        $providersIndex = $this->taggedProvider->getProvidersIndex();

        $connections = [];
        foreach ($providersConnection as $providerConnection) {
            /** @var ProviderConnection $providerConnection */
            $connection = $this->convertProviderConnectionToDto($providerConnection);

            $indexes = [];
            foreach ($providersIndex as $providerIndex) {
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
        return new Connection($providerConnection->getName(), $providerConnection->getIndexPrefix(), $providerConnection->getClientConfig());
    }

    private function convertProviderIndexToDto(ProviderIndexInterface $providerIndex, Connection $connection): Index
    {
        $className = $providerIndex->getClassName();
        $name = $providerIndex->getIndexName($className);
        $additionalConfig = $providerIndex->getAdditionalConfig();

        // prepare decorators
        $decorators = $this->taggedProvider->getDecoratorsIndex();
        foreach ($decorators as $k => $decorator) {
            if ($decorator instanceof ProviderBaseInterface and $decorator->getClassName() !== $className) {
                unset($decorators[$k]);
            }
        }

        // decorate
        $mapping = $settings = [];
        foreach ($decorators as $decorator) {
            $mapping = $decorator->getIndexMapping($className, $mapping);
            $settings = $decorator->getIndexSettings($className, $settings);
        }

        $mapping = $this->decorateMappingItems($className, $mapping, $decorators);

        return new Index($className, $connection, $name, $mapping, $settings, $additionalConfig);
    }

    /** @param DecoratorIndexInterface[] $decorators */
    private function decorateMappingItems(string $className, array $mapping, array $decorators): array
    {
        foreach ($mapping as $mappingItemKey => $mappingItem) {
            $mappingItemType = $mappingItem['type'] ?? null;

            foreach ($decorators as $decorator) {
                $mapping[$mappingItemKey] = $decorator->getIndexMappingItem($className, $mappingItem, $mappingItemKey, $mappingItemType);
            }

            if ('object' == $mappingItemType || 'nested' == $mappingItemType) {
                $mapping[$mappingItemKey]['properties'] = $this->decorateMappingItems($className, $mappingItem['properties'], $decorators);
            }
        }

        return $mapping;
    }
}
