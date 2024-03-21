<?php

namespace FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Decorator;

use FHPlatform\ConfigBundle\Config\Decorator\DecoratorIndex;
use FHPlatform\ConfigBundle\DTO\Index;

class DecoratorIndex_First extends DecoratorIndex
{
    public function priority(): int
    {
        return 1;
    }

    public function getIndexMapping(Index $index, array $mapping): array
    {
        $mapping['decorator_index_mapping_level_1'] = [1];

        return $mapping;
    }

    public function getIndexSettings(Index $index, array $settings): array
    {
        $settings['decorator_index_settings_level_1'] = [1];

        return $settings;
    }
}
