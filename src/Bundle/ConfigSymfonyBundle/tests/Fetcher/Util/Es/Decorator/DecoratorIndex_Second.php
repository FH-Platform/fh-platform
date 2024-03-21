<?php

namespace FHPlatform\ConfigSymfonyBundle\Tests\Fetcher\Util\Es\Decorator;

use FHPlatform\ConfigBundle\Config\Decorator\DecoratorIndex;
use FHPlatform\ConfigBundle\DTO\Index;

class DecoratorIndex_Second extends DecoratorIndex
{
    public function priority(): int
    {
        return -1;
    }

    public function getIndexMapping(Index $index, array $mapping): array
    {
        $mapping['decorator_index_mapping_level_-1'] = [-1];

        return $mapping;
    }

    public function getIndexSettings(Index $index, array $settings): array
    {
        $settings['decorator_index_settings_level_-1'] = [-1];

        return $settings;
    }
}
