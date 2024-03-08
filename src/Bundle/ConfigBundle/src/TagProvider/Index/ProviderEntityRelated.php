<?php

namespace FHPlatform\ConfigBundle\TagProvider\Index;

use FHPlatform\ConfigBundle\TagProvider\Decorator\Interface\EntityRelatedInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('symfony_es.provider.entity_related')]
abstract class ProviderEntityRelated extends ProviderBase implements EntityRelatedInterface
{
    public function getEntityRelatedEntities($entity, $entitiesRelated): array
    {
        return $entitiesRelated;
    }
}
