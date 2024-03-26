<?php

namespace FHPlatform\Component\DoctrineToEs\Builder;

use Doctrine\Common\Collections\Collection;
use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\FieldsAssociationsProvider\AssociationsProvider;
use FHPlatform\Component\DoctrineToEs\FieldsAssociationsProvider\FieldsProvider;
use FHPlatform\Component\Persistence\Persistence\PersistenceInterface;

class DataBuilder
{
    public function __construct(
        private readonly PersistenceInterface $persistence,
        private readonly AssociationsProvider $associationsProvider,
        private readonly FieldsProvider $fieldsProvider,
    ) {
    }

    public function build(Index $index, $entity, array $config): array
    {
        $className = $index->getClassName();

        $data = [];

        return $this->buildRecursive($className, $entity, $config, $data);
    }

    private function buildRecursive(string $className, $entity, array $config, array &$data = [], $levels = []): array
    {
        $associations = $this->associationsProvider->provide($className, $config);

        foreach ($associations as $association) {
            $type = $association['type'];
            $fieldName = $association['fieldName'];
            $columnName = $association['columnName'];
            $targetEntity = $association['targetEntity'];
            $getter = $association['getter'];
            $typeEs = $association['typeEs'];
            $configAssociation = $association['configAssociation'];

            if ($entity = $this->persistence->refresh($entity)) {
                $value = $entity->{$getter}();

                // fetch related entities
                $entitiesRelated = $this->fetchEntitiesRelated($value);

                // recursive call for all related entities
                foreach ($entitiesRelated as $k => $entityRelated) {
                    if ('object' === $typeEs) {
                        $levelsNew = array_merge($levels, [$columnName]);
                    } else {
                        $levelsNew = array_merge($levels, [$columnName, $k]);
                    }

                    $this->buildRecursive($entityRelated::class, $entityRelated, $configAssociation, $data, $levelsNew);
                }

                // store related entities into data
                if ('object' === $typeEs) {
                    // for object default null
                    $value = null;

                    if ($entityRelated = ($entitiesRelated[0] ?? null)) {
                        $value = $this->generateData($entityRelated::class, $entityRelated, $configAssociation);
                    }
                } else {
                    // for nested default []
                    $value = [];

                    foreach ($entitiesRelated as $k => $entityRelated) {
                        $value[$k] = $this->generateData($entityRelated::class, $entityRelated, $configAssociation);
                    }
                }

                $data = $this->relatedInNestedLevel($data, $columnName, $value, $levels);
            }
        }

        if (0 === count($levels)) {
            $dataFields = $this->generateData($className, $entity, $config);
            $data = array_merge($dataFields, $data);
        }

        return $data;
    }

    private function fetchEntitiesRelated($entityRelated): array
    {
        $entitiesRelatedAll = [];
        if ($entityRelated instanceof Collection || is_array($entityRelated)) {
            $entitiesRelated = $entityRelated;
            foreach ($entitiesRelated as $entityRelated) {
                if ($entityRelated = $this->persistence->refresh($entityRelated)) {
                    $entitiesRelatedAll[] = $entityRelated;
                }
            }
        } else {
            if ($entityRelated = $this->persistence->refresh($entityRelated)) {
                $entitiesRelatedAll[] = $entityRelated;
            }
        }

        return $entitiesRelatedAll;
    }

    private function relatedInNestedLevel($esDataRoot, $columnName, $value, $levels): array
    {
        $levelTmp = array_merge($levels, [$columnName]);

        $output = [];
        $temp = &$output;

        foreach ($levelTmp as $level) {
            $temp[$level] = [];
            $temp = &$temp[$level];
        }

        $temp = $value;

        return array_replace_recursive($output, $esDataRoot);
    }

    public function generateData(string $className, $entity, array $config): array
    {
        if (!$entity = $this->persistence->refresh($entity)) {
            return [];
        }

        $fields = $this->fieldsProvider->provide($className, $config);

        $esDataFields = [];
        foreach ($fields as $field) {
            $type = $field['type'];
            $columnName = $field['columnName'];
            $getter = $field['getter'];

            $value = $entity->{$getter}();

            if ($value instanceof \DateTimeInterface) {
                $value = $value->format(\DateTimeInterface::ATOM); // ISO8601
            }

            $esDataFields[$columnName] = $value;
        }

        return $esDataFields;
    }
}
