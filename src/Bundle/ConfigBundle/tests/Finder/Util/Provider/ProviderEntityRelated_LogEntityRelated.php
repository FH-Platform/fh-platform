<?php

namespace FHPlatform\ConfigBundle\Tests\Finder\Util\Provider;

use FHPlatform\ConfigBundle\TagProvider\Index\ProviderEntityRelated;
use FHPlatform\ConfigBundle\Tests\Finder\Util\Entity\LogEntityRelated;
use FHPlatform\ConfigBundle\Tests\Finder\Util\Entity\LogIndex;

class ProviderEntityRelated_LogEntityRelated extends ProviderEntityRelated
{
    public function getClassName(): string
    {
        return LogEntityRelated::class;
    }
}
