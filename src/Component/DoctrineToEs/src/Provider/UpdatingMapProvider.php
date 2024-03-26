<?php

namespace FHPlatform\Component\DoctrineToEs\Provider;

use Doctrine\ORM\EntityManagerInterface;
use FHPlatform\Component\DoctrineToEs\FieldsAssociationsProvider\FieldsProvider;

class UpdatingMapProvider
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly FieldsProvider $fieldsProvider,
    ) {
    }

    public function provide($configClassNames): array
    {
        $updatingMap = [];
        $updatingMapReversed = [];

        // for each nestable(and searchable) provider calculate config for update
        foreach ($configClassNames as $className => $configClassName) {
            if (null === $configClassName) {
                continue;
            }

            // add for root class
            // $this->addToUpdatingMap($className, $className, $config, '', $updatingMap);

            $this->calculateForAssociations($className, $className, $configClassName, '', $updatingMap, $updatingMapReversed);
        }

        return [$updatingMap, $updatingMapReversed];
    }

    private function calculateForAssociations(string $className, string $classNameCurrent, array $configCurrent, string $associationsNamesAccumulated, &$updatingMap, &$updatingMapReversed): void
    {
        $classNameMetadata = $this->entityManager->getClassMetadata($classNameCurrent);

        foreach ($classNameMetadata->associationMappings as $associationMapping) {
            $fieldName = $associationMapping['fieldName'];
            $targetEntity = $associationMapping['targetEntity'];
            $associationNameCurrent = $associationMapping['inversedBy'] ?? $associationMapping['mappedBy'];

            foreach ($configCurrent as $association => $associationsRelated) {
                if ($association === $fieldName) {
                    $targetEntities = $this->fetchTargetEntities($targetEntity);

                    foreach ($targetEntities as $targetEntity) {
                        // store parent association
                        if ($associationsNamesAccumulated) {
                            $associationNameCurrent = $associationNameCurrent.'.'.$associationsNamesAccumulated;
                        }

                        // add to updating map
                        $changedFields = array_keys($this->fieldsProvider->provide($targetEntity, $associationsRelated));
                        $updatingMap[$targetEntity][$className] = ['relations' => $associationNameCurrent, 'changed_fields' => $changedFields];
                        $updatingMapReversed[$className][$targetEntity] = ['relations' => $associationNameCurrent, 'changed_fields' => $changedFields];

                        // recursive call for next relation
                        $this->calculateForAssociations($className, $targetEntity, $associationsRelated, $associationNameCurrent, $updatingMap, $updatingMapReversed);
                    }
                }
            }
        }
    }

    private function fetchTargetEntities($targetEntity): array
    {
        // support discriminatorMap entities (roles and settings)
        $targetEntities = [];
        $classNameMetadata = $this->entityManager->getClassMetadata($targetEntity);
        foreach ($classNameMetadata->discriminatorMap as $k => $v) {
            $targetEntities[] = $v;
        }

        if (0 === count($targetEntities)) {
            $targetEntities[] = $targetEntity;
        }

        return $targetEntities;
    }
}
