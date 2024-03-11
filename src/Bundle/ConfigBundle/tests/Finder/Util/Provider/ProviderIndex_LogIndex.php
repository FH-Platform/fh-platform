<?php

namespace FHPlatform\ConfigBundle\Tests\Finder\Util\Provider;

use FHPlatform\ConfigBundle\TagProvider\Index\ProviderIndex;
use FHPlatform\ConfigBundle\Tests\Finder\Util\Entity\LogEntity;
use FHPlatform\ConfigBundle\Tests\Finder\Util\Entity\LogIndex;

class ProviderIndex_LogIndex extends ProviderIndex
{
    public function getClassName(): string
    {
        return LogIndex::class;
    }
}
