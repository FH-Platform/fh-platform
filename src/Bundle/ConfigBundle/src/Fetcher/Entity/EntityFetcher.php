<?php

namespace FHPlatform\ConfigBundle\Fetcher\Entity;

use Doctrine\ORM\EntityManagerInterface;
use FHPlatform\ConfigBundle\DTO\Entity;
use FHPlatform\ConfigBundle\DTO\Index;
use FHPlatform\ConfigBundle\Fetcher\IndexFetcher;
use FHPlatform\ConfigBundle\Tag\Decorator\Interface\DecoratorEntityInterface;
use FHPlatform\ConfigBundle\Tag\Provider\Interface\ProviderBaseInterface;
use FHPlatform\ConfigBundle\Tagged\TaggedProvider;
use FHPlatform\UtilBundle\Helper\EntityHelper;

class EntityFetcher
{
    public function __construct(
        private readonly TaggedProvider $taggedProvider,
        private readonly IndexFetcher $indexFetcher,
        private readonly EntityManagerInterface $entityManager,
        private readonly EntityHelper $entityHelper,
    ) {
    }

    public function fetch($entity): Entity  // TODO rename to DTO
    {
        $className = $entity::class;

        // TODO
        $identifier = null;
        if (!$this->entityManager->getMetadataFactory()->isTransient($className)) {
            $identifier = $this->entityHelper->getIdentifierValue($entity);
        }

        // TODO throw error if class not available for ES

        // prepare decorators
        $decorators = $this->taggedProvider->getDecoratorsEntity();
        foreach ($decorators as $k => $decorator) {
            if ($decorator instanceof ProviderBaseInterface and $decorator->getClassName() !== $className) {
                unset($decorators[$k]);
            }
        }

        $index = $this->indexFetcher->fetchIndexesByClassName($className)[0];
        $mapping = $index->getMapping();

        // decorate
        $data = [];
        $shouldBeIndexed = true;
        foreach ($decorators as $decorator) {
            $data = $decorator->getEntityData($index, $entity, $data, $mapping);
            $shouldBeIndexed = $decorator->getEntityShouldBeIndexed($entity, $shouldBeIndexed);
        }
        $data = $this->decorateDataItems($index, $className, $data, $mapping, $decorators);

        // return
        return new Entity($index, $identifier, $data, $shouldBeIndexed);
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
