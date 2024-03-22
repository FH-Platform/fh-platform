<?php

namespace FHPlatform\Component\Persistence\Persistence;

interface PersistenceInterface
{
    public function getIdentifierValue(mixed $entity): mixed;

    public function refreshByClassNameId(string $className, mixed $identifierValue): mixed;

    public function getAllIds(string $className): array;
}
