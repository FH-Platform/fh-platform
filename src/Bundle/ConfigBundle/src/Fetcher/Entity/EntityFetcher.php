<?php

namespace FHPlatform\ConfigBundle\Fetcher\Entity;

use Doctrine\ORM\EntityManagerInterface;
use FHPlatform\ConfigBundle\DTO\Entity;
use FHPlatform\ConfigBundle\Fetcher\IndexFetcher;
use FHPlatform\ConfigBundle\Tag\Decorator\Interface\DecoratorEntityInterface;
use FHPlatform\ConfigBundle\Tag\Decorator\Interface\DecoratorIndexInterface;
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

        $identifier = null;
        if (!$this->entityManager->getMetadataFactory()->isTransient($className)) {
            $identifier = $this->entityHelper->getIdentifierValue($entity);
        }

        // TODO throw error if class not available for ES

        // prepare decorators
        $decorators = $this->taggedProvider->getDecoratorsEntity();
        foreach ($decorators as $k =>$decorator) {
            if ($decorator instanceof ProviderBaseInterface and $decorator->getClassName() !== $className) {
                unset($decorators[$k]);
            }
        }

        $index = $this->indexFetcher->fetch($className);

        // decorate
        $data = [];
        $shouldBeIndexed = true;
        foreach ($decorators as $decorator) {
            $data = $decorator->getEntityData($entity, $data, $index->getMapping());
            $shouldBeIndexed = $decorator->getEntityShouldBeIndexed($entity, $shouldBeIndexed);
        }

        $data = $this->decorateDataItems($className, $data, $decorators);

        // return
        return new Entity($entity, $className, $identifier, $index, $data, $shouldBeIndexed);
    }

    /** @param DecoratorEntityInterface[] $decorators */
    private function decorateDataItems(string $className, array $data, array $decorators): ?array
    {
        foreach ($data as $key => $dataItem) {

        }

        return $data;
    }
}
