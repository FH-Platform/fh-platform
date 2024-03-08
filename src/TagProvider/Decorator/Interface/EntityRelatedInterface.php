<?php

namespace FHPlatform\ConfigBundle\TagProvider\Decorator\Interface;

interface EntityRelatedInterface
{
    public function getEntityRelatedEntities($entity, $entitiesRelated): array;
}
