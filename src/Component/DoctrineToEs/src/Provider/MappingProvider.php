<?php

namespace FHPlatform\Component\DoctrineToEs\Provider;

use FHPlatform\Component\DoctrineToEs\DoctrineToEsFacade;
use FHPlatform\Component\DoctrineToEs\FieldsAssociationsProvider\AssociationsProvider;
use FHPlatform\Component\DoctrineToEs\FieldsAssociationsProvider\FieldsProvider;

class MappingProvider
{
    public function __construct(
        private readonly AssociationsProvider $associationsProvider,
        private readonly FieldsProvider $fieldsProvider,
    ) {
    }

    public function provide(string $className, array $configClassName, bool $sameLevel, array &$mapping = [], $levels = []): array
    {
        $associations = $this->associationsProvider->provide($className, $configClassName);

        foreach ($associations as $association) {
            $columnName = $association['columnName'];
            $type = $association['type'];
            $targetEntity = $association['targetEntity'];
            $configAssociations = $association['configAssociation'];

            $mappingAssociation = $this->generateMapping($targetEntity, $configAssociations);

            $this->provide($targetEntity, $configAssociations, $sameLevel, $mapping, array_merge($levels, [$columnName]));

            if ($sameLevel) {
                $mapping = $this->relatedInSameLevel($mapping, $columnName, $type, $mappingAssociation, $levels);
            } else {
                $mapping = $this->relatedInNestedLevel($mapping, $columnName, $type, $mappingAssociation, $levels);
            }
        }

        if (0 === count($levels)) {
            $mappingAssociation = $this->generateMapping($className, $configClassName);
            $mapping = array_merge($mappingAssociation, $mapping);
        }

        return $mapping;
    }

    private function relatedInSameLevel($esMappingRoot, $columnName, $type, $esMappingFields, $levels): array
    {
        $esMappingAssociation[$columnName] = [
            'type' => (in_array($type, [1, 2]) ? 'object' : 'nested'),
            'properties' => $esMappingFields,
        ];

        return array_merge($esMappingRoot, $esMappingAssociation);
    }

    private function relatedInNestedLevel($esMappingRoot, $columnName, $type, $esMappingFields, $levels): array
    {
        $levelTmp = array_merge($levels, [$columnName]);
        $output = [];
        $temp = &$output;

        foreach ($levelTmp as $level) {
            $temp[$level]['properties'] = [];
            $temp2 = &$temp[$level];
            $temp = &$temp[$level]['properties'];
        }

        $temp2['type'] = (in_array($type, [1, 2]) ? 'object' : 'nested');
        $temp = $esMappingFields;

        return array_merge_recursive($output, $esMappingRoot);
    }

    private function generateMapping(string $className, $configAssociations): array
    {
        $fields = $this->fieldsProvider->provide($className, $configAssociations);

        $mappingFields = [];
        foreach ($fields as $field) {
            $type = $field['type'];
            $columnName = $field['columnName'];

            $mappingFields[$columnName] = ['type' => DoctrineToEsFacade::DOCTRINE_TYPES_TO_ES_TYPES[$type]];
        }

        return $mappingFields;
    }
}
