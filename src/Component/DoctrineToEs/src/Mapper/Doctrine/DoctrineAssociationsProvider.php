<?php

namespace FHPlatform\Component\DoctrineToEs\Mapper\Doctrine;

use Doctrine\ORM\EntityManagerInterface;

// return doctrine associations array from doctrine metadata
class DoctrineAssociationsProvider
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function provide(string $className): array
    {
        $classNameMetadata = $this->entityManager->getClassMetadata($className);

        $doctrineAssociations = [];
        foreach ($classNameMetadata->associationMappings as $associationMapping) {
            $type = $associationMapping['type'];
            $fieldName = $associationMapping['fieldName'];
            $columnName = $fieldName;
            $targetEntity = $associationMapping['targetEntity'];
            $inversedBy = $associationMapping['inversedBy'] ?? $associationMapping['mappedBy'];

            $doctrineAssociations[] = [
                'type' => $type,
                'fieldName' => $fieldName,
                'columnName' => $columnName,
                'targetEntity' => $targetEntity,
                'inversedBy' => $inversedBy,
            ];
        }

        return $doctrineAssociations;
    }
}
