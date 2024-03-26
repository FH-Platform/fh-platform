<?php

namespace FHPlatform\Component\DoctrineToEs\FieldsAssociationsProvider\ConfigProvider;

use FHPlatform\Component\DoctrineToEs\Builder\MappingBuilder;
use FHPlatform\Component\DoctrineToEs\FieldsAssociationsProvider\DoctrineProvider\DoctrineFieldsProvider;

// return fields from doctrine-to-es config
class ConfigFieldsProvider
{
    public function __construct(
        private readonly DoctrineFieldsProvider $doctrineFieldsProvider,
    ) {
    }

    public function provide(string $className, array $associations): array
    {
        $configFields = [];

        foreach ($associations as $key => $value) {
            if (is_int($key)) {
                $configFields[$value] = $value;
            }
        }

        // put all supported field if any is not set
        if (0 === count($configFields)) {
            $doctrineFields = $this->doctrineFieldsProvider->provide($className);

            foreach ($doctrineFields as $doctrineField) {
                $type = $doctrineField['type'];
                $fieldName = $doctrineField['fieldName'];

                $methodGetter = 'get'.ucfirst($fieldName);
                $methodGetterBoolean = 'is'.ucfirst($fieldName);

                // check and set getter
                $getter = null;
                if (method_exists($className, $methodGetter)) {
                    $getter = $methodGetter;
                }

                if (!$getter && 'boolean' === $type && method_exists($className, $methodGetterBoolean)) {
                    $getter = $methodGetterBoolean;
                }

                if ($getter && isset(MappingBuilder::DOCTRINE_TYPES_TO_ES_TYPES[$type])) {
                    $configFields[$fieldName] = $fieldName;
                }
            }
        }

        return $configFields;
    }
}
