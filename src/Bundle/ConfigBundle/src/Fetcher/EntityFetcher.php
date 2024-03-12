<?php

namespace FHPlatform\ConfigBundle\Fetcher;

use FHPlatform\ConfigBundle\Fetcher\DTO\Entity;
use FHPlatform\ConfigBundle\Tag\Data\Provider\ProviderEntity;
use FHPlatform\ConfigBundle\Tagged\TaggedProvider;

class EntityFetcher
{
    public function __construct(
        private readonly TaggedProvider $taggedProvider,
        private readonly IndexFetcher $indexFetcher,
    ) {
    }

    public function fetch($entity): Entity
    {
        $className = $entity::class;

        // TODO throw error if class not available for ES

        // prepare decorators
        $decorators = $this->taggedProvider->getDecoratorsEntity();

        // decorate
        $data = [];
        $shouldBeIndexed = true;
        foreach ($decorators as $decorator) {
            if ($decorator instanceof ProviderEntity and $decorator->getClassName() !== $className) {
                continue;
            }

            $data = $decorator->getEntityData($entity, $data);
            $shouldBeIndexed = $decorator->getEntityShouldBeIndexed($entity, $shouldBeIndexed);
        }

        $index = $this->indexFetcher->fetch($className);

        // return
        return new Entity($entity, $index, $data, $shouldBeIndexed);
    }
}
