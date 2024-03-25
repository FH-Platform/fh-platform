<?php

namespace FHPlatform\Component\Persistence\Tests\Helper;

use FHPlatform\Component\Config\Config\ConfigProvider;
use FHPlatform\Component\PersistenceDoctrine\Persistence\PersistenceDoctrine;
use FHPlatform\Component\PersistenceDoctrine\Tests\TestCase;
use FHPlatform\Component\PersistenceDoctrine\Tests\Util\Entity\User;
use FHPlatform\Component\PersistenceDoctrine\Tests\Util\Entity\UserUuid;
use FHPlatform\Component\PersistenceDoctrine\Tests\Util\Es\Config\Connections\ProviderDefaultConnection;
use FHPlatform\Component\PersistenceDoctrine\Tests\Util\Es\Config\Provider\UserProviderEntity;

class EntityHelperTest extends TestCase
{
    protected function setUp(): void
    {
        ConfigProvider::$includedClasses = [
            ProviderDefaultConnection::class,
            UserProviderEntity::class,
        ];

        parent::setUp();
    }

    public function testHelper(): void
    {
        /** @var PersistenceDoctrine $persistenceDoctrine */
        $persistenceDoctrine = $this->container->get(PersistenceDoctrine::class);

        $user = new User();
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $userUuid = new UserUuid();
        $this->entityManager->persist($userUuid);
        $this->entityManager->flush();

        $this->assertEquals('id', $persistenceDoctrine->getIdentifierName($user));
        $this->assertEquals('uuid', $persistenceDoctrine->getIdentifierName($userUuid));

        $this->assertEquals($user->getId(), $persistenceDoctrine->getIdentifierValue($user));
        $this->assertEquals($userUuid->getUuid(), $persistenceDoctrine->getIdentifierValue($userUuid));

        $this->assertEquals($user, $persistenceDoctrine->refresh($user));
        $this->assertEquals($userUuid, $persistenceDoctrine->refresh($userUuid));

        $this->assertEquals($user, $persistenceDoctrine->refreshByClassNameId(User::class, $user->getId()));
        $this->assertEquals($userUuid, $persistenceDoctrine->refreshByClassNameId(UserUuid::class, $userUuid->getUuid()));

        $this->assertEquals(User::class, $persistenceDoctrine->getRealClass("Proxies\__CG__\\".User::class));
        $this->assertEquals(UserUuid::class, $persistenceDoctrine->getRealClass("Proxies\__CG__\\".UserUuid::class));
    }
}
