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
        $providersConnection = $this->configProvider->getProvidersConnection();
        $providersIndex = $this->configProvider->getProvidersIndex();

        $connections = [];
        foreach ($providersConnection as $providerConnection) {
            $connection = $this->convertProviderConnectionToDto($providerConnection);
            $connection = $this->decorateConnectionPreIndex($providerConnection, $connection);
            $indexes = [];
            foreach ($providersIndex as $providerIndex) {
                if ($providerIndex->getConnection() === $providerConnection->getName()) {
                    $indexes[] = $this->convertProviderIndexToDto($providerIndex, $connection);
                }
            }

            $connection->setIndexes($indexes);
            $connection = $this->decorateConnectionPostIndex($providerConnection, $connection);

            $connections[] = $connection;
        }

        return $connections;
    }

    public function fetchIndexesByConnectionNameAndClassName(string $connectionName, string $className): Index
    {
        $indexes = [];
        foreach ($this->build() as $connection) {
            if ($connection->getName() !== $connectionName) {
                continue;
            }

            foreach ($connection->getIndexes() as $index) {
                if ($index->getClassName() === $className) {
                    return $index;
                }
            }
        }

        throw new \Exception('Index not found');
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
        $connection = new Connection(
            $providerConnection->getName(),
            $providerConnection->getIndexPrefix(),
            $providerConnection->getClientConfig(),
        );

        return $connection;
    }

    private function decorateConnectionPreIndex(ProviderConnection $providerConnection, Connection $connection): Connection
    {
        $decorators = $this->configProvider->getDecoratorsConnection();

        $configAdditionalPreIndex = [];
        foreach ($decorators as $decorator) {
            if ($decorator instanceof ProviderConnection and $decorator->getName() !== $providerConnection->getName()) {
                continue;
            }

            $configAdditionalPreIndex = $decorator->getConfigAdditionalPreIndex($connection, $configAdditionalPreIndex);
        }

        $connection->setConfigAdditionalPreIndex($configAdditionalPreIndex);

        return $connection;
    }

    private function decorateConnectionPostIndex(ProviderConnection $providerConnection, Connection $connection): Connection
    {
        $decorators = $this->configProvider->getDecoratorsConnection();

        $configAdditionalPreIndex = [];
        foreach ($decorators as $decorator) {
            if ($decorator instanceof ProviderConnection and $decorator->getName() !== $providerConnection->getName()) {
                continue;
            }

            $configAdditionalPreIndex = $decorator->getConfigAdditionalPostIndex($connection, $configAdditionalPreIndex);
        }

        $connection->setConfigAdditionalPostIndex($configAdditionalPreIndex);

        return $connection;
    }

    private function convertProviderIndexToDto(ProviderIndexInterface $providerIndex, Connection $connection): Index
    {
        $className = $providerIndex->getClassName();
        $name = $providerIndex->getIndexName($className);
        $nameWithPrefix = $connection->getPrefix().$name;

        // TODO
        $index = new Index($connection, $className, true, $name, $nameWithPrefix);

        // prepare decorators
        $decorators = $this->configProvider->getDecoratorsIndex(ProviderBaseInterface::class, $className);

        // decorate config additional
        $configAdditional = $this->decorateIndexConfigAdditional($index, $decorators);
        $index->setConfigAdditional($configAdditional);

        // decorate mapping and settings
        list($mapping, $settings) = $this->decorateIndex($index, $decorators);

        // decorate mapping items
        $mapping = $this->decorateMappingItems($index, $mapping, $decorators);

        $index->setMapping($mapping);
        $index->setSettings($settings);

        return $index;
    }

    /** @param DecoratorIndexInterface[] $decorators */
    private function decorateIndex(Index $index, array $decorators): array
    {
        $mapping = $settings = $configAdditional = [];
        foreach ($decorators as $decorator) {
            $mapping = $decorator->getIndexMapping($index, $mapping);
            $settings = $decorator->getIndexSettings($index, $settings);
        }

        return [$mapping, $settings];
    }

    /** @param DecoratorIndexInterface[] $decorators */
    private function decorateIndexConfigAdditional(Index $index, array $decorators): array
    {
        $configAdditional = [];
        foreach ($decorators as $decorator) {
            $configAdditional = $decorator->getConfigAdditional($index, $configAdditional);
        }

        return $configAdditional;
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
