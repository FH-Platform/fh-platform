<?php

namespace FHPlatform\ConfigBundle\Tests\Util\Helper;

use FHPlatform\ConfigBundle\TaggedProvider\TaggedProvider;

class TaggedProviderMock extends TaggedProvider
{
    public static array $included = [];

    public function getIncludedClasses(): array
    {
        return self::$included;
    }
}
