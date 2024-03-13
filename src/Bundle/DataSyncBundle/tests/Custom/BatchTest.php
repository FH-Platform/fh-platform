<?php

namespace FHPlatform\DataSyncBundle\Tests\Custom;

use FHPlatform\ConfigBundle\Fetcher\IndexFetcher;
use FHPlatform\ConfigBundle\Tagged\TaggedProvider;
use FHPlatform\DataSyncBundle\Tests\TestCase;
use FHPlatform\DataSyncBundle\Tests\Util\Entity\User;
use FHPlatform\DataSyncBundle\Tests\Util\Es\Config\Connections\ProviderDefaultConnection;
use FHPlatform\DataSyncBundle\Tests\Util\Es\Config\Provider\UserProviderEntity;

class BatchTest extends TestCase
{
    protected function setUp(): void
    {
        TaggedProvider::$includedClasses = [
            ProviderDefaultConnection::class,
            UserProviderEntity::class,
        ];

        parent::setUp();
    }

    public function testSomething(): void
    {
        /** @var IndexFetcher $indexFetcher */
        $indexFetcher = $this->container->get(IndexFetcher::class);
        $index = $indexFetcher->fetchIndexesByClassName(User::class)[0];

        $this->indexClient->recreateIndex($index);

        $this->prepareUsers();

        $this->entityManager->flush();

        $this->assertEquals(1, 1);
    }

    private function prepareUsers(): void
    {
        for ($i = 0; $i < 10; ++$i) {
            $user = new User();
            $user->setNameString('name_'.$i);
            $this->entityManager->persist($user);
        }
    }
}
