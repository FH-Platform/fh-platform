<?php

namespace FHPlatform\Bundle\PersistenceBundle\Persistence;

interface PersistenceInterface
{
    public function refreshByClassNameId(string $className, mixed $identifierValue): mixed;

    public function getAllIds(string $className): array;
}
