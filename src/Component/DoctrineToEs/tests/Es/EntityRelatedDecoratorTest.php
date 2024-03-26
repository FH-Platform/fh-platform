<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Es;

use FHPlatform\Component\Config\Config\ConfigProvider;
use FHPlatform\Component\DoctrineToEs\Es\DataDecorator;
use FHPlatform\Component\DoctrineToEs\Es\EntityRelatedDecorator;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\Setting;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Es\ProviderDefaultConnection;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Es\UserProviderEntity;
use FHPlatform\Component\Persistence\DTO\ChangedEntityDTO;

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


        $data = ($this->entitiesRelatedBuilder->build($user));
        //TODO test

        // dd($this->documentBuilder->build($user, User::class, 1, ChangedEntityDTO::TYPE_UPDATE)->getData());

        $this->assertEquals(1, 1);
    }
}
