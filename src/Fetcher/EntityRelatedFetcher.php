<?php

namespace FHPlatform\ConfigBundle\Fetcher;

use FHPlatform\ConfigBundle\Fetcher\DTO\EntityRelated;
use FHPlatform\ConfigBundle\TaggedProvider\TaggedProvider;
use FHPlatform\ConfigBundle\TagProvider\Decorator\EntityDecorator;
use FHPlatform\ConfigBundle\TagProvider\Index\ProviderEntity;

class EntityRelatedFetcher
{
    public function __construct(
        private readonly TaggedProvider $taggedProvider,
        private readonly EntityFetcher $entityFetcher,
    ) {
    }

    public function fetch($entity): EntityRelated
    {
        $className = $entity::class;

        // TODO throw error if class not available for ES

        // prepare decorators
        $decorators = $this->taggedProvider->getDecoratorsEntity();

        // decorate
        $data = [];
        $entitiesRelated = [];
        foreach ($decorators as $decorator) {
            if ($decorator instanceof ProviderEntity and $decorator->getClassName() !== $className) {
                continue;
            }

            /** @var EntityDecorator $decorator */
            $entitiesRelated = $decorator->getEntityRelatedEntities($entity, $entitiesRelated);
        }

        // return
        return new EntityRelated($entity, $entitiesRelated);
    }
}
