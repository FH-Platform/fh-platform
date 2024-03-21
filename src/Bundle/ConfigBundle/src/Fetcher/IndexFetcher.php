<?php

namespace FHPlatform\ConfigBundle\Fetcher;

use FHPlatform\ConfigBundle\Config\ConfigProvider;

class IndexFetcher
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
