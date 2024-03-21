<?php

namespace FHPlatform\ConfigSymfonyBundle\Tests\Service\Namer;

use FHPlatform\ConfigBundle\Util\Namer\IndexNamer;
use FHPlatform\ConfigSymfonyBundle\Tests\TestCase;

class IndexNamerTest extends TestCase
{
    public function testSomething(): void
    {
        $name = new IndexNamer();

        $this->assertEquals('user', $name->getName('App\Entity\User'));
        $this->assertEquals('user_two', $name->getName('App\Entity\UserTwo'));
        $this->assertEquals('user_two', $name->getName('App\Entity\User_Two'));
        $this->assertEquals('user', $name->getName('App\Entity\Namespace\User'));
    }
}
