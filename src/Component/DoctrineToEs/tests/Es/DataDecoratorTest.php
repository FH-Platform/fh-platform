<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Es;

use FHPlatform\Component\Config\Config\ConfigProvider;
use FHPlatform\Component\DoctrineToEs\Es\DataDecorator;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\Setting;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Es\ProviderDefaultConnection;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Es\UserProviderEntity;
use FHPlatform\Component\Persistence\DTO\ChangedEntityDTO;

class DataDecoratorTest extends TestCaseEs
{
    protected function setUp(): void
    {
        ConfigProvider::$includedClasses = [
            ProviderDefaultConnection::class,
            UserProviderEntity::class,
            DataDecorator::class,
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

        $this->assertEquals([
            'testInteger' => 16,
            'setting' => [
                'testFloat' => 18.16,
            ],
        ], $this->documentBuilder->build($user, User::class, 1, ChangedEntityDTO::TYPE_UPDATE)->getData());
    }
}
