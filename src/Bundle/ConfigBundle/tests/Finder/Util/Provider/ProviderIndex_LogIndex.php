<?php

namespace FHPlatform\ConfigBundle\Tests\Finder\Util\Provider;

use FHPlatform\ConfigBundle\Tag\Provider\ProviderIndex;
use FHPlatform\ConfigBundle\Tests\Finder\Util\Entity\LogIndex;

class ProviderIndex_LogIndex extends ProviderIndex
{
    public function getClassName(): string
    {
        return LogIndex::class;
    }
}
