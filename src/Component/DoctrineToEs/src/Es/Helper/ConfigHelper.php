<?php

namespace FHPlatform\Component\DoctrineToEs\Es\Helper;

use FHPlatform\Component\Config\DTO\Index;

class ConfigHelper
{
    public function getConfig(Index $index)
    {
        if (null !== ($config = ($index->getConfigAdditional()['doctrine_to_es'] ?? null))) {
            return $config;
        }

        return null;
    }
}
