<?php

namespace FHPlatform\ConfigBundle\Builder;

use FHPlatform\ConfigBundle\Config\ConfigProvider;
use FHPlatform\ConfigBundle\Config\Connection\ProviderConnection;
use FHPlatform\ConfigBundle\Config\Decorator\Interface\DecoratorIndexInterface;
use FHPlatform\ConfigBundle\Config\Provider\Interface\ProviderBaseInterface;
use FHPlatform\ConfigBundle\Config\Provider\Interface\ProviderIndexInterface;
use FHPlatform\ConfigBundle\DTO\Connection;
use FHPlatform\ConfigBundle\DTO\Index;

class ConnectionsBuilder
{
    public function __construct(
        private readonly ConfigProvider $taggedProvider,
    ) {
    }

    /** @return Connection[] */
    public function build(): array
    {
        $providersConnection = $this->taggedProvider->getConnections();
        $providersIndex = $this->taggedProvider->getProvidersIndex();

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
        $decorators = $this->taggedProvider->getDecoratorsIndex(ProviderBaseInterface::class, $className);

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
