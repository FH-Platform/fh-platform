<?php

namespace FHPlatform\ConfigBundle\Fetcher\Entity;

use FHPlatform\ConfigBundle\DTO\Entity;
use FHPlatform\ConfigBundle\DTO\Index;
use FHPlatform\ConfigBundle\Fetcher\Global\ConnectionsFetcher;
use FHPlatform\ConfigBundle\Tag\Decorator\Interface\DecoratorEntityInterface;
use FHPlatform\ConfigBundle\Tag\Provider\Interface\ProviderBaseInterface;
use FHPlatform\ConfigBundle\Tagged\TaggedProvider;
use FHPlatform\UtilBundle\Helper\EntityHelper;

class EntityFetcher
{
    public function __construct(
        private readonly TaggedProvider $taggedProvider,
        private readonly ConnectionsFetcher $connectionsFetcher,
        private readonly EntityHelper $entityHelper,
    ) {
    }

    public function fetchDelete(string $className, mixed $identifier): Entity  // TODO rename to DTO
    {
        $index = $this->connectionsFetcher->fetchIndexesByClassName($className)[0];

        return new Entity($index, $identifier, [], false);
    }

    public function fetchUpsert($entity): Entity  // TODO rename to DTO
    {
        $className = $entity::class;

        // TODO
        $identifier = $this->entityHelper->getIdentifierValue($entity);

        // TODO throw error if class not available for ES

        $index = $this->connectionsFetcher->fetchIndexesByClassName($className)[0];

        // prepare decorators
        $decorators = $this->taggedProvider->getDecoratorsEntity(ProviderBaseInterface::class, $className);

        // decorate data and should_be_indexed
        list($data, $shouldBeIndexed) = $this->decorateDataShouldBeIndexed($index, $entity, $decorators);

        // decorate data items
        $data = $this->decorateDataItems($index, $className, $data, $index->getMapping(), $decorators);

        // return
        return new Entity($index, $identifier, $data, $shouldBeIndexed);
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
                $data[$mappingItemKey] = $this->decorateDataItems($index, $entity, $dataItem, $mapping[$mappingItemKey]['properties'], $decorators);
            } elseif ('nested' == $mappingItemType) {
                foreach ($dataItem as $k2 => $item) {
                    $data[$mappingItemKey][$k2] = $this->decorateDataItems($index, $entity, $item, $mapping[$mappingItemKey]['properties'], $decorators);
                }
            }
        }

        return $data;
    }
}
