<?php

namespace FHPlatform\ClientBundle\Tests\Util\Es\Config\Provider;

use FHPlatform\ConfigBundle\TagProvider\Index\ProviderEntity;

class Test2ProviderEntity extends ProviderEntity
{
    public function getClassName(): string
    {
        return 'Test2';
    }
}
