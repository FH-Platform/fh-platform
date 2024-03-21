<?php

namespace FHPlatform\Bundle\ConfigBundle\Builder;

use FHPlatform\Bundle\ConfigBundle\Config\ConfigProvider;
use FHPlatform\Bundle\ConfigBundle\Config\Decorator\Interface\DecoratorEntityInterface;
use FHPlatform\Bundle\ConfigBundle\Config\Provider\Interface\ProviderBaseInterface;
use FHPlatform\Bundle\ConfigBundle\DTO\Entity;
use FHPlatform\Bundle\ConfigBundle\DTO\Index;
use FHPlatform\Bundle\UtilBundle\Helper\EntityHelper;

class EntityBuilder
{
    public function __construct(
        private readonly ConfigProvider $configProvider,
        private readonly ConnectionsBuilder $connectionsBuilder,
        private readonly EntityHelper $entityHelper,
    ) {
    }

    public function buildForDelete(string $className, mixed $identifier): Entity  // TODO rename to DTO
    {
        $index = $this->connectionsBuilder->fetchIndexesByClassName($className)[0];

        return new Entity($index, $identifier, [], false);
    }

    public function buildForUpsert($entity): Entity  // TODO rename to DTO
    {
        $className = $entity::class;

        // TODO
        $identifier = $this->entityHelper->getIdentifierValue($entity);

        // TODO throw error if class not available for ES

        $index = $this->connectionsBuilder->fetchIndexesByClassName($className)[0];

        // prepare decorators
        $decorators = $this->configProvider->getDecoratorsEntity(ProviderBaseInterface::class, $className);

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
