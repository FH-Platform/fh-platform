<?php

namespace FHPlatform\Component\DoctrineToEs\FieldsAssociationsProvider;

use FHPlatform\Component\DoctrineToEs\DoctrineToEsFacade;
use FHPlatform\Component\DoctrineToEs\FieldsAssociationsProvider\ConfigProvider\ConfigFieldsProvider;
use FHPlatform\Component\DoctrineToEs\FieldsAssociationsProvider\DoctrineProvider\DoctrineFieldsProvider;

class FieldsProvider
{
    public function __construct(
        private readonly DoctrineFieldsProvider $doctrineFieldsProvider,
        private readonly ConfigFieldsProvider $configFieldsProvider,
    ) {
    }

    public function provide(string $className, $config): array
    {
        $doctrineFields = $this->doctrineFieldsProvider->provide($className);
        $configFields = $this->configFieldsProvider->provide($className, $config);

        $fields = [];
        foreach ($doctrineFields as $doctrineField) {
            $type = $doctrineField['type'];
            $fieldName = $doctrineField['fieldName'];

            // skip not included field
            $configField = $configFields[$fieldName] ?? null;
            unset($configFields[$fieldName]);
            if (null === $configField) {
                continue;
            }

            // if field type is not supported throw exception
            if (!isset(DoctrineToEsFacade::DOCTRINE_TYPES_TO_ES_TYPES[$type])) {
                throw new \Exception('Field "'.$fieldName.'" in class "'.$className.'" with type="'.$type.'" is not supported');
            }

            // get data with getter, for boolean try with boolean getter
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

            if (!$getter) {
                throw new \Exception('Getter "'.$methodGetter.'" in class "'.$className.'" for field "'.$fieldName.'" can not be found');
            }

            // add additional data
            $doctrineField = array_merge($doctrineField, [
                'getter' => $getter,
            ]);

            $fields[$fieldName] = $doctrineField;
        }

        // throw error if there is field in config which can not be found in doctrine mapping
        if (count($configFields) > 0) {
            $configField = reset($configFields);
            throw new \Exception('Field "'.$configField.'" in class "'.$className.'" not found');
        }

        return $fields;
    }
}
