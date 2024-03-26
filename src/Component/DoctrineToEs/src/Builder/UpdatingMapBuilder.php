<?php

namespace FHPlatform\Component\DoctrineToEs\Builder;

use Doctrine\ORM\EntityManagerInterface;
use FHPlatform\Component\Config\DTO\Connection;
use FHPlatform\Component\DoctrineToEs\FieldsAssociationsProvider\FieldsProvider;

class UpdatingMapBuilder
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly FieldsProvider $fieldsProvider,
    ) {
    }

    public function build(array $connections): array
    {
        $updatingMap = [];

        // for each nestable(and searchable) provider calculate config for update
        foreach ($connections as $connectionName => $classNames) {
            foreach ($classNames as $className => $config) {
                // add for root class
                // $this->addToUpdatingMap($className, $className, $config, '', $updatingMap);

                $this->calculateForAssociations($className, $className, $config, '', $updatingMap['default']);
            }
        }

        return $updatingMap;
    }

    private function calculateForAssociations(string $className, string $classNameCurrent, array $configCurrent, string $associationsNamesAccumulated, &$updatingMap): void
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

                        // recursive call for next relation
                        $this->calculateForAssociations($className, $targetEntity, $associationsRelated, $associationNameCurrent, $updatingMap);
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
