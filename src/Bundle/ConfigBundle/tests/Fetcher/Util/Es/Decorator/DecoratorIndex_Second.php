<?php

namespace FHPlatform\ConfigBundle\Tests\Fetcher\Util\Es\Decorator;

use FHPlatform\ConfigBundle\DTO\Index;
use FHPlatform\ConfigBundle\Tag\Decorator\DecoratorIndex;

class DecoratorIndex_Second extends DecoratorIndex
{
    public function priority(): int
    {
        return -1;
    }

    public function getIndexMapping(string $className, array $mapping): array
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
