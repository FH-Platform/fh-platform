<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Provider;

use FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Interface\EntityRelatedInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('symfony_es.provider.entity_related')]
abstract class ProviderEntityRelated extends ProviderBase implements EntityRelatedInterface
{
    public function getEntityRelatedEntities($entity, $entitiesRelated): array
    {
        return $entitiesRelated;
    }
}
