<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Decorator;

use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\Setting;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;

class DataDecoratorTest extends TestCaseEs
{
    public function testSomething(): void
    {
        $setting = new Setting();
        $setting->setTestFloat(18.16);
        $this->save([$setting]);

        $user = new User();
        $user->setTestInteger(16);
        $user->setSetting($setting);
        $this->save([$user]);

        $indexes = $this->connectionsBuilder->fetchIndexesByClassName(User::class);

        $this->assertEquals([
            'testInteger' => 16,
            'setting' => [
                'testFloat' => 18.16,
            ],
        ], $this->documentBuilder->buildForEntity($indexes[0], $user, User::class, 1)->getData());
    }
}
