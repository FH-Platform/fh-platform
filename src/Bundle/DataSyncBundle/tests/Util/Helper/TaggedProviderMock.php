<?php

namespace FHPlatform\DataSyncBundle\Tests\Util\Helper;

use FHPlatform\ConfigBundle\TaggedProvider\TaggedProvider;

class TaggedProviderMock extends TaggedProvider
{
    public static array $included = [];

    public function getIncludedClasses(): array
    {
        dd(1111);

        return self::$included;
    }
}
