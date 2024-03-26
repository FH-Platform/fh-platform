<?php

namespace FHPlatform\Component\DoctrineToEs\FieldsAssociationsProvider\DoctrineProvider;

use Doctrine\ORM\EntityManagerInterface;

// return doctrine fields array from doctrine metadata
class DoctrineFieldsProvider
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function provide(string $className): array
    {
        $classNameMetadata = $this->entityManager->getClassMetadata($className);

        $doctrineFields = [];
        foreach ($classNameMetadata->fieldMappings as $name => $fieldMapping) {
            $type = $fieldMapping['type'];
            $fieldName = $fieldMapping['fieldName'];
            $columnName = $fieldMapping['columnName'];

            $doctrineFields[] = [
                'type' => $type,
                'fieldName' => $fieldName,
                'columnName' => $columnName,
            ];
        }

        return $doctrineFields;
    }
}
