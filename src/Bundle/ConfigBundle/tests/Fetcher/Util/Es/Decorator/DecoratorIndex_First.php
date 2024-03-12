<?php

namespace FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Decorator;

use FHPlatform\ConfigBundle\Service\Namer\IndexNamer;
use FHPlatform\ConfigBundle\Tag\Data\Decorator\DecoratorIndex;

class DecoratorIndex_First extends DecoratorIndex
{
    public function priority(): int
    {
        return 1;
    }

    public function getIndexMapping(string $className, array $mapping): array
    {
        $mapping['index_mapping_level_1'] = 1;

        return $mapping;
    }

    public function getIndexSettings(string $className, array $settings): array
    {
        $settings['index_settings_level_1'] = 1;

        return $settings;
    }
}
