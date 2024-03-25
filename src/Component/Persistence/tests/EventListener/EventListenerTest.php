<?php

namespace FHPlatform\Component\Persistence\Tests\EventListener;

use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Config\ConfigProvider;
use FHPlatform\Component\Persistence\Tests\TestCase;
use FHPlatform\Component\Persistence\Tests\Util\Entity\User;
use FHPlatform\Component\Persistence\Tests\Util\Es\Config\Connections\ProviderDefaultConnection;
use FHPlatform\Component\Persistence\Tests\Util\Es\Config\Provider\UserProviderEntity;

class EventListenerTest extends TestCase
{
    protected function setUp(): void
    {
        ConfigProvider::$includedClasses = [
            ProviderDefaultConnection::class,
            UserProviderEntity::class,
        ];

        parent::setUp();
    }

    public function testSomething(): void
    {
        /** @var ConnectionsBuilder $connectionsBuilder */
        $connectionsBuilder = $this->container->get(ConnectionsBuilder::class);
        $index = $connectionsBuilder->fetchIndexesByClassName(User::class)[0];

        $this->indexClient->recreateIndex($index);
        $this->assertCount(0, $this->findEsBy($index, 'nameString', 'test'));
        $this->assertCount(0, $this->findEsBy($index, 'nameString', 'test2'));

        // create
        $user = new User();
        $user->setNameString('test');
        $this->save([$user]);
        $this->assertCount(1, $this->findEsBy($index, 'nameString', 'test'));
        $this->assertCount(0, $this->findEsBy($index, 'nameString', 'test2'));

        // update
        $user->setNameString('test2');
        $this->save([$user]);
        $this->assertCount(0, $this->findEsBy($index, 'nameString', 'test'));
        $this->assertCount(1, $this->findEsBy($index, 'nameString', 'test2'));

        // delete
        $this->delete([$user]);
        $this->assertCount(0, $this->findEsBy($index, 'nameString', 'test'));
        $this->assertCount(0, $this->findEsBy($index, 'nameString', 'test2'));
    }
}
