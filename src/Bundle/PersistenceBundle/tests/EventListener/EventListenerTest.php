<?php

namespace FHPlatform\Bundle\PersistenceBundle\Tests\EventListener;

use FHPlatform\Bundle\PersistenceBundle\Tests\TestCase;
use FHPlatform\Bundle\PersistenceBundle\Tests\Util\Entity\User;
use FHPlatform\Bundle\PersistenceBundle\Tests\Util\Es\Config\Connections\ProviderDefaultConnection;
use FHPlatform\Bundle\PersistenceBundle\Tests\Util\Es\Config\Provider\UserProviderEntity;
use FHPlatform\Component\Config\Builder\ConnectionsBuilder;
use FHPlatform\Component\Config\Config\ConfigProvider;

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
