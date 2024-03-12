<?php

namespace FHPlatform\ConfigBundle\Fetcher;

use FHPlatform\ConfigBundle\Fetcher\DTO\EntityRelated;
use FHPlatform\ConfigBundle\Tag\Data\Decorator\DecoratorEntity;
use FHPlatform\ConfigBundle\Tag\Data\Provider\Interface\ProviderEntityRelatedInterface;
use FHPlatform\ConfigBundle\Tag\Data\Provider\ProviderEntity;
use FHPlatform\ConfigBundle\Tag\Data\Provider\ProviderEntityRelated;
use FHPlatform\ConfigBundle\Tagged\TaggedProvider;

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
        $decorators = $this->taggedProvider->getDecoratorsEntityRelated();

        // decorate
        $data = [];
        $entitiesRelated = [];
        foreach ($decorators as $decorator) {
            if ($decorator instanceof ProviderEntityRelatedInterface and $decorator->getClassName() !== $className) {
                continue;
            }

            /** @var DecoratorEntity $decorator */
            $entitiesRelated = $decorator->getEntityRelatedEntities($entity, $entitiesRelated);
        }

        // return
        return new EntityRelated($entity, $entitiesRelated);
    }
}
