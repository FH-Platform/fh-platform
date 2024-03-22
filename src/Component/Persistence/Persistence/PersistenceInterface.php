<?php

namespace FHPlatform\Component\Persistence\Persistence;

interface PersistenceInterface
{
    public function refreshByClassNameId(string $className, mixed $identifierValue): mixed;

    public function getAllIds(string $className): array;
}
