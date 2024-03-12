<?php

namespace FHPlatform\ConfigBundle\Tests\Fetcher;

use Doctrine\Common\Collections\ArrayCollection;
use FHPlatform\ConfigBundle\Fetcher\EntityRelatedFetcher;
use FHPlatform\ConfigBundle\Tests\TestCase;
use FHPlatform\ConfigBundle\Tests\Util\Entity\Role;
use FHPlatform\ConfigBundle\Tests\Util\Entity\User;
use FHPlatform\ConfigBundle\Tests\Util\Es\Config\Connections\ProviderDefaultConnection;
use FHPlatform\ConfigBundle\Tests\Util\Es\Config\Provider\RoleProviderEntity;
use FHPlatform\ConfigBundle\Tests\Util\Es\Config\Provider\UserProviderEntity;
use FHPlatform\ConfigBundle\Tests\Util\Helper\TaggedProviderMock;

class EntityRelatedFetcherTest extends TestCase
{
    protected function setUp(): void
    {
        TaggedProviderMock::$included = [
            ProviderDefaultConnection::class,
            RoleProviderEntity::class,
            UserProviderEntity::class,
        ];

        parent::setUp();
    }

    public function testFetchEntity(): void
    {
        $role = new Role();
        $role->setNameString('test');

        $this->entityManager->persist($role);
        $this->entityManager->flush();

        $user = new User();
        $user->setNameString('test');
        $user->setRoles(new ArrayCollection([$role]));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->entityManager->refresh($role);

        /** @var EntityRelatedFetcher $entityRelatedFetcher */
        $entityRelatedFetcher = $this->container->get(EntityRelatedFetcher::class);

        $entityRelated = $entityRelatedFetcher->fetch($role);
        $this->assertEquals(1, count($entityRelated->getEntitiesRelated()));
    }
}
