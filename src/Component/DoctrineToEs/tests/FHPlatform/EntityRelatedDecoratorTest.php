<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\FHPlatform;

use FHPlatform\Component\Config\Config\ConfigProvider;
use FHPlatform\Component\DoctrineToEs\FHPlatform\EntityRelatedDecorator;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\Setting;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\DoctrineToEs\Tests\Util\FHPlatform\ProviderDefaultConnection;
use FHPlatform\Component\DoctrineToEs\Tests\Util\FHPlatform\UserProviderEntity;

class EntityRelatedDecoratorTest extends TestCaseEs
{
    protected function setUp(): void
    {
        ConfigProvider::$includedClasses = [
            ProviderDefaultConnection::class,
            UserProviderEntity::class,
            EntityRelatedDecorator::class,
        ];

        parent::setUp();
    }

    public function testSomething(): void
    {
        $setting = new Setting();
        $setting->setTestFloat(18.16);
        $this->save([$setting]);

        $user = new User();
        $user->setTestInteger(16);
        $user->setSetting($setting);
        $this->save([$user]);

        $data = $this->entitiesRelatedBuilder->build($setting, ['testInteger']);
        $this->assertEquals([], $data);

        $data = $this->entitiesRelatedBuilder->build($setting, ['testFloat']);
        $this->assertEquals([$user], $data);
    }
}
