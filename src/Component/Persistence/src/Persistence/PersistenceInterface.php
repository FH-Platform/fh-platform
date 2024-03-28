<?php

namespace FHPlatform\Component\Persistence\Persistence;

interface PersistenceInterface
{
    public function isEntity(string $className): bool;

    public function getIdentifierName(mixed $entity): ?string;

    public function getIdentifierValue(mixed $entity): mixed;

    public function refresh(mixed $entity): mixed;

    public function refreshByClassNameId(string $className, mixed $identifierValue): mixed;

    public function getAllIds(string $className): array;

    public function getEntities(string $className, array $identifiers): array;

    public function getRealClass(string $className): string;
}
