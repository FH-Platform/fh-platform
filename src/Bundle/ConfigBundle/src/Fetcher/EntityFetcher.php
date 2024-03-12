<?php

namespace FHPlatform\ConfigBundle\Fetcher;

use Doctrine\ORM\EntityManagerInterface;
use FHPlatform\ConfigBundle\DTO\Entity;
use FHPlatform\ConfigBundle\Tag\Data\Provider\Interface\ProviderBaseInterface;
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

    public function fetch($entity): Entity
    {
        $className = $entity::class;

        $identifier = null;
        if (!$this->entityManager->getMetadataFactory()->isTransient($className)) {
            $identifier = $this->entityHelper->getIdentifierValue($entity);
        }

        // TODO throw error if class not available for ES

        // prepare decorators
        $decorators = $this->taggedProvider->getDecoratorsEntity();

        $index = $this->indexFetcher->fetch($className);

        // decorate
        $data = [];
        $shouldBeIndexed = true;
        foreach ($decorators as $decorator) {
            if ($decorator instanceof ProviderBaseInterface and $decorator->getClassName() !== $className) {
                continue;
            }

            $data = $decorator->getEntityData($entity, $data, $index->getMapping());
            $shouldBeIndexed = $decorator->getEntityShouldBeIndexed($entity, $shouldBeIndexed);
        }

        // return
        return new Entity($entity, $className, $identifier, $index, $data, $shouldBeIndexed);
    }
}
