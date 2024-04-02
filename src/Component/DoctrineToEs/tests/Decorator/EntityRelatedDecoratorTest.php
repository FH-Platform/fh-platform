<?php

namespace FHPlatform\Component\DoctrineToEs\Tests\Decorator;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\Setting\Setting;
use FHPlatform\Component\DoctrineToEs\Tests\Util\Entity\User;
use FHPlatform\Component\Persistence\Event\ChangedEntity;

class EntityRelatedDecoratorTest extends TestCaseEs
{
    public function testSomething(): void
    {
        /** @var ConnectionsBuilder $connectionsBuilder */
        $connectionsBuilder = $this->container->get(ConnectionsBuilder::class);
        $connection = $connectionsBuilder->build()[0];

        $setting = new Setting();
        $setting->setTestFloat(18.16);
        $this->save([$setting]);

        $user = new User();
        $user->setTestInteger(16);
        $user->setSetting($setting);
        $this->save([$user]);

        $data = $this->entitiesRelatedBuilder->build($connection, $setting, ChangedEntity::TYPE_UPDATE, ['testInteger']);
        $this->assertEquals([], $data);

        $data = $this->entitiesRelatedBuilder->build($connection, $setting, ChangedEntity::TYPE_UPDATE, ['testFloat']);
        $this->assertEquals([$user], $data);
    }
}
