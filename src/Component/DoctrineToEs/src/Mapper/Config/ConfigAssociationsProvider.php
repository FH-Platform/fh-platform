<?php

namespace FHPlatform\Component\DoctrineToEs\Mapper\Config;

// return associations from doctrine-to-es config
class ConfigAssociationsProvider
{
    public function provide(string $className, array $configClassName): array
    {
        $configAssociations = [];

        foreach ($configClassName as $key => $value) {
            if (!is_int($key)) {
                $configAssociations[$key] = $value;
            }
        }

        return $configAssociations;
    }
}
