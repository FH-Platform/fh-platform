<?php

namespace FHPlatform\Bundle\ConfigBundle\Builder;

use FHPlatform\Bundle\ConfigBundle\Config\ConfigProvider;

class IndexBuilder
{
    public function __construct(
        private readonly ConfigProvider $configProvider,
    ) {
    }

    public function fetchClassNamesIndex(): array
    {
        $classNames = [];
        foreach ($this->configProvider->getProvidersEntity() as $provider) {
            $classNames[$provider->getClassName()] = $provider->getClassName();
        }

        return $classNames;
    }
}
