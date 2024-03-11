<?php

namespace FHPlatform\ConfigBundle\TagProvider\Data\Decorator\Interface;

interface EntityRelatedInterface
{
    public function getEntityRelatedEntities($entity, $entitiesRelated): array;
}
