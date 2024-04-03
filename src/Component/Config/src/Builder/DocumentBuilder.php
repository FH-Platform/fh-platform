<?php

namespace FHPlatform\Component\Config\Builder;

use FHPlatform\Component\Config\Config\ConfigProvider;
use FHPlatform\Component\Config\Config\Decorator\Interface\DecoratorEntityInterface;
use FHPlatform\Component\Config\Config\Provider\Interface\ProviderBaseInterface;
use FHPlatform\Component\Config\DTO\Document;
use FHPlatform\Component\Config\DTO\Index;

class DocumentBuilder
{
    public function __construct(
        private readonly ConfigProvider $configProvider,
        private readonly ConnectionsBuilder $connectionsBuilder,
    ) {
    }

    public function buildRaw($className, $data, $identifier, $type): Document
    {
        $index = $this->connectionsBuilder->fetchIndexesByClassName($className)[0];

        return new Document($index, $identifier, $data, $type);
    }

    public function buildForEntity($entity, $className, $identifier, $type): Document
    {
        // TODO throw error if class not available for ES

        $index = $this->connectionsBuilder->fetchIndexesByClassName($className)[0];

        if (Document::TYPE_DELETE === $type or !$entity) {
            return new Document($index, $identifier, [], Document::TYPE_DELETE);
        }

        // prepare decorators
        $decorators = $this->configProvider->getDecoratorsEntity(ProviderBaseInterface::class, $className);

        // decorate data and should_be_indexed
        list($data, $shouldBeIndexed) = $this->decorateDataShouldBeIndexed($index, $entity, $decorators);

        if (!$shouldBeIndexed) {
            return new Document($index, $identifier, [], Document::TYPE_DELETE);
        }

        // decorate data items
        $data = $this->decorateDataItems($index, $className, $data, $index->getMapping(), $decorators);

        // return
        return new Document($index, $identifier, $data, $type);
    }

    private function decorateDataShouldBeIndexed(Index $index, mixed $entity, $decorators): array
    {
        $data = [];
        $shouldBeIndexed = true;
        foreach ($decorators as $decorator) {
            $data = $decorator->getEntityData($index, $entity, $data);
            $shouldBeIndexed = $decorator->getEntityShouldBeIndexed($index, $entity, $shouldBeIndexed);
        }

        return [$data, $shouldBeIndexed];
    }

    /** @param DecoratorEntityInterface[] $decorators */
    private function decorateDataItems(Index $index, mixed $entity, array $data, array $mapping, array $decorators): array
    {
        foreach ($data as $mappingItemKey => $dataItem) {
            $mappingItem = $mapping[$mappingItemKey] ?? null;
            $mappingItemType = $mappingItem['type'] ?? null;

            foreach ($decorators as $decorator) {
                $data[$mappingItemKey] = $decorator->getEntityDataItem($index, $entity, $dataItem, $mappingItem, $mappingItemKey, $mappingItemType);
            }

            if ('object' == $mappingItemType) {
                $data[$mappingItemKey] = $this->decorateDataItems($index, $entity, $dataItem ?? [], $mapping[$mappingItemKey]['properties'], $decorators);
            } elseif ('nested' == $mappingItemType) {
                foreach ($dataItem as $k2 => $item) {
                    $data[$mappingItemKey][$k2] = $this->decorateDataItems($index, $entity, $item, $mapping[$mappingItemKey]['properties'], $decorators);
                }
            }
        }

        return $data;
    }
}
