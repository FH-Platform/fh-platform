<?php

namespace FHPlatform\Component\DoctrineToEs\Builder;

use FHPlatform\Component\Config\DTO\Index;
use FHPlatform\Component\DoctrineToEs\DoctrineToEsFacade;
use FHPlatform\Component\DoctrineToEs\FieldsAssociationsProvider\AssociationsProvider;
use FHPlatform\Component\DoctrineToEs\FieldsAssociationsProvider\FieldsProvider;

class MappingBuilder
{
    public function __construct(
        private readonly AssociationsProvider $associationsProvider,
        private readonly FieldsProvider $fieldsProvider,
    ) {
    }

    public function build(Index $index, array $config): array
    {
        $className = $index->getClassName();

        $mapping = [];

        return $this->buildRecursive($className, $config, $mapping);
    }

    private function buildRecursive(string $className, array $config, array &$mapping, $levels = []): array
    {
        $associations = $this->associationsProvider->provide($className, $config);

        foreach ($associations as $association) {
            $columnName = $association['columnName'];
            $type = $association['type'];
            $targetEntity = $association['targetEntity'];
            $configAssociations = $association['configAssociation'];

            $mappingAssociation = $this->generateMapping($targetEntity, $configAssociations);

            $this->buildRecursive($targetEntity, $configAssociations, $mapping, array_merge($levels, [$columnName]));

            $mapping = $this->relatedInNestedLevel($mapping, $columnName, $type, $mappingAssociation, $levels);
        }

        if (0 === count($levels)) {
            $mappingAssociation = $this->generateMapping($className, $config);
            $mapping = array_merge($mappingAssociation, $mapping);
        }

        return $mapping;
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
