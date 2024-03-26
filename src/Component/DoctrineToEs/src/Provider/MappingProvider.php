<?php

namespace FHPlatform\Component\DoctrineToEs\Provider;

use FHPlatform\Component\Config\DTO\Index;
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

    public function provide(Index $index, array $config): array
    {
        $className = $index->getClassName();

        $mapping = [];

        return $this->provide2($className, $config, $mapping, []);
    }

    private function provide2(string $className, array $config, array &$mapping, $levels)
    {
        $associations = $this->associationsProvider->provide($className, $config);

        foreach ($associations as $association) {
            $columnName = $association['columnName'];
            $type = $association['type'];
            $targetEntity = $association['targetEntity'];
            $configAssociations = $association['configAssociation'];

            $mappingAssociation = $this->generateMapping($targetEntity, $configAssociations);

            $this->provide2($targetEntity, $configAssociations, $mapping, array_merge($levels, [$columnName]));

            $mapping = $this->relatedInSameLevel($mapping, $columnName, $type, $mappingAssociation, $levels);
        }

        if (0 === count($levels)) {
            $mappingAssociation = $this->generateMapping($className, $config);
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
