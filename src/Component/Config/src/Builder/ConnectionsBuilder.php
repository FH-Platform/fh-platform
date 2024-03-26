<?php

namespace FHPlatform\Component\Config\Builder;

use FHPlatform\Component\Config\Config\ConfigProvider;
use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorIndexInterface;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderBaseInterface;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderIndexInterface;
use FHPlatform\Component\Config\Config\Provider\ProviderConnection;
use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\Config\DTO\Index;

class ConnectionsBuilder
{
    public function __construct(
        private readonly ConfigProvider $configProvider,
    ) {
    }

    /** @return Connection[] */
    public function build(): array
    {
        $providersConnection = $this->configProvider->getConnections();
        $providersIndex = $this->configProvider->getProvidersIndex();

        $connections = [];
        foreach ($providersConnection as $providerConnection) {
            $connection = $this->convertProviderConnectionToDto($providerConnection);

            $indexes = [];
            foreach ($providersIndex as $providerIndex) {
                if ($providerIndex->getConnection() === $providerConnection->getName()) {
                    $indexes[] = $this->convertProviderIndexToDto($providerIndex, $connection);
                }
            }

            $connection->setIndexes($indexes);

            $connections[] = $connection;
        }

        return $connections;
    }

    /** @return Index[] */
    public function fetchIndexesByClassName(string $className): array
    {
        $indexes = [];
        foreach ($this->build() as $connection) {
            foreach ($connection->getIndexes() as $index) {
                if ($index->getClassName() === $className) {
                    $indexes[] = $index;
                }
            }
        }

        return $indexes;
    }

    private function convertProviderConnectionToDto(ProviderConnection $providerConnection): Connection
    {
        return new Connection(
            $providerConnection->getName(),
            $providerConnection->getIndexPrefix(),
            $providerConnection->getClientConfig(),
            $providerConnection->getAdditionalConfig()
        );
    }

    private function convertProviderIndexToDto(ProviderIndexInterface $providerIndex, Connection $connection): Index
    {
        $className = $providerIndex->getClassName();
        $name = $providerIndex->getIndexName($className);
        $nameWithPrefix = $connection->getPrefix().$name;
        $additionalConfig = $providerIndex->getAdditionalConfig();

        $index = new Index($connection, $className, $name, $nameWithPrefix, $additionalConfig);

        // prepare decorators
        $decorators = $this->configProvider->getDecoratorsIndex(ProviderBaseInterface::class, $className);

        // decorate mapping and settings
        list($mapping, $settings) = $this->decorateMappingSettings($index, $decorators);

        // decorate mapping items
        $mapping = $this->decorateMappingItems($index, $mapping, $decorators);

        $index->setMapping($mapping);
        $index->setSettings($settings);

        return $index;
    }

    private function decorateMappingSettings(Index $index, array $decorators): array
    {
        $mapping = $settings = [];
        foreach ($decorators as $decorator) {
            $mapping = $decorator->getIndexMapping($index, $mapping);
            $settings = $decorator->getIndexSettings($index, $settings);
        }

        return [$mapping, $settings];
    }

    /** @param DecoratorIndexInterface[] $decorators */
    private function decorateMappingItems(Index $index, array $mapping, array $decorators): array
    {
        foreach ($mapping as $mappingItemKey => $mappingItem) {
            $mappingItemType = $mappingItem['type'] ?? null;

            foreach ($decorators as $decorator) {
                $mapping[$mappingItemKey] = $decorator->getIndexMappingItem($index, $mappingItem, $mappingItemKey, $mappingItemType);
            }

            if ('object' == $mappingItemType || 'nested' == $mappingItemType) {
                $mapping[$mappingItemKey]['properties'] = $this->decorateMappingItems($index, $mappingItem['properties'], $decorators);
            }
        }

        return $mapping;
    }
}
